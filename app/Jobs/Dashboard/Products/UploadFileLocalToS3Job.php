<?php

namespace App\Jobs\Dashboard\Products;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class UploadFileLocalToS3Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly Product $product,
        public readonly string $userId,
        public readonly string $filePath,
        public readonly string $description,
        public readonly string $collectionName,
    ) {}

    public function handle(): void
    {
        if ($this->product->getMedia('attachment')->isEmpty()) {
            $revision = $this->product->revisions()->create([
                'user_id' => $this->userId,
                'key'     => 'anexo',
            ]);

            $mediaAttachmentProduct = $revision->addMediaFromDisk($this->filePath, 'local')
                ->withCustomProperties(['description' => $this->description])
                ->toMediaCollection('attachmentProduct');

            $revision->update(['new_value' => $mediaAttachmentProduct]);

        } else {
            $currentAttachment = $this->product->getMedia('attachment')->last();

            $revision = $this->product->revisions()->create([
                'user_id'   => $this->userId,
                'key'       => 'anexo',
                'old_value' => $currentAttachment,
            ]);

            $mediaAttachmentProduct = $revision->addMediaFromDisk($this->filePath, 'local')
                ->usingName($currentAttachment->name)
                ->withCustomProperties(['description' => $currentAttachment->custom_properties['description'] ?? ''])
                ->toMediaCollection('attachmentProduct');

            $revision->update(['new_value' => $mediaAttachmentProduct]);
        }

        \Storage::delete($this->filePath);
    }
}
