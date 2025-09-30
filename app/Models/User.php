<?php

namespace App\Models;

use App\Enums\TypePersonEnum;
use App\Notifications\VerifyUserNotification;
use App\Observers\UserObserver;
use App\Traits\{Auditable, HasAddress, HasSchemalessAttributes};
use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, HasOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements HasMedia
{
    use Auditable;
    use HasAddress;
    use HasApiTokens;
    use HasFactory;
    use HasSchemalessAttributes;
    use InteractsWithMedia;
    use Notifiable;
    use SoftDeletes;

    public const NAME_TOKEN_FROM_BANKING = 'token-banking';

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'two_factor_code',
        'password',
    ];

    protected $casts = [
        'birthday'              => 'date',
        'email_verified_at'     => 'datetime',
        'verified_at'           => 'datetime',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'deleted_at'            => 'datetime',
        'two_factor_expires_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'document_number',
        'birthday',
        'email_verified_at',
        'password',
        'verified',
        'verified_at',
        'verification_token',
        'two_factor',
        'two_factor_code',
        'remember_token',
        'two_factor_expires_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::created(function (self $user) {
            if (auth()->check()) {
                $user->verified    = 1;
                $user->verified_at = Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));
                $user->save();
            } elseif (! $user->verification_token) {
                $token     = Str::random(64);
                $usedToken = self::where('verification_token', $token)->first();

                while ($usedToken) {
                    $token     = Str::random(64);
                    $usedToken = self::where('verification_token', $token)->first();
                }

                $user->verification_token = $token;
                $user->save();

                $registrationRole = config('panel.registration_default_role');

                if (! $user->roles()->get()->contains($registrationRole)) {
                    $user->roles()->attach($registrationRole);
                }

                if (! $user->verified) {
                    $user->notify(new VerifyUserNotification($user));
                }
            }
        });
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function shops(): BelongsToMany
    {
        return $this->morphedByMany(Shop::class, 'userable');
    }

    public function shop(): ?Shop
    {
        return Shop::where('owner_id', $this->id)->first();
    }

    public function shopUser(): HasOne
    {
        return $this->hasOne(Shop::class, 'owner_id');
    }

    public function affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class);
    }

    public function coproducers(): HasMany
    {
        return $this->hasMany(Coproducer::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 50, 50);
        $this->addMediaConversion('preview')->fit(Fit::Crop, 120, 120);
        $this->addMediaConversion('webp')->format('webp');
    }

    public function typePerson(): Attribute
    {
        return Attribute::make(
            get: function () {
                $typePerson = $this->getValueSchemalessAttributes('type_person');

                return TypePersonEnum::getTranslation($typePerson);
            }
        )->shouldCache();
    }

    public function getPhotoAttribute(): ?Media
    {
        $file = $this->getMedia('photo')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->photo->url ?? 'https://eu.ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF'
        )->shouldCache();
    }

    public function getFirstNameAttribute(): string
    {
        return explode(' ', $this->name)[0];
    }

    public function generateTwoFactorCode(): void
    {
        $this->timestamps            = false;
        $this->two_factor_code       = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(15)->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        $this->save();
    }

    public function resetTwoFactorCode(): void
    {
        $this->timestamps            = false;
        $this->two_factor_code       = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->roles->contains('id', 1);
    }

    public function getEmailVerifiedAtAttribute($value): ?string
    {
        return $value
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format'))
            : null;
    }

    public function setEmailVerifiedAtAttribute($value): void
    {
        $this->attributes['email_verified_at'] = $value
            ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s')
            : null;
    }

    public function setPasswordAttribute($input): void
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function getVerifiedAtAttribute($value): ?string
    {
        return $value
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format'))
            : null;
    }

    public function setVerifiedAtAttribute($value): void
    {
        $this->attributes['verified_at'] = $value
            ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s')
            : null;
    }

    public function getTwoFactorExpiresAtAttribute($value): ?string
    {
        return $value
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format'))
            : null;
    }

    public function setTwoFactorExpiresAtAttribute($value): void
    {
        $this->attributes['two_factor_expires_at'] = $value
            ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s')
            : null;
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function hasRole(string|array $role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('title', $role);
        }

        $role = collect($role);

        return (bool) $role->intersect($this->roles)->count();
    }

    public function documentNumber(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->attributes['document_number'] = preg_replace('/[^0-9]/', '', $value)
        );
    }

    public function phoneNumber(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->attributes['phone_number'] = preg_replace('/[^0-9]/', '', $value)
        );
    }

    public function shortName(): Attribute
    {
        return Attribute::make(function () {
            get: return explode(' ', trim($this->name))[0] . ' ' . explode(' ', trim($this->name))[count(explode(' ', trim($this->name))) - 1];
        })->shouldCache();
    }

    public function firstName(): Attribute
    {
        return Attribute::make(function () {
            get: return explode(' ', trim($this->name))[0];
        })->shouldCache();
    }

    public function lastName(): Attribute
    {
        return Attribute::make(function () {
            get: return explode(' ', trim($this->name))[count(explode(' ', trim($this->name))) - 1];
        })->shouldCache();
    }

    public function hasFullRegistration(): Attribute
    {
        return Attribute::make(
            get: function () {
                return filled($this->name)
                    && filled($this->email)
                    && filled($this->document_number)
                    && filled($this->birthday)
                    && filled($this->phone_number)
                    && filled($this->shopUser);
            }
        )->shouldCache();
    }

}
