<?php

namespace App\Jobs\Dashboard\Members;

use App\Models\Product;
use App\Models\User;
use App\Services\Members\SuitMembersApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class RemoveRelationCourseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected Product $product;
    protected User $user;

    public function __construct(Product $product, User $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    public function handle(): void
    {
        try {
            $this->stepsSendSuitMembers();
        } catch (\Throwable $th) {
            Log::channel('members')->error(
                'Erro ao enviar para api de membros para inativar usúario.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'DeactivateMemberJob.handle',
                    'trace' => $th->getTraceAsString()
                ]
            );
            throw $th;
        }
    }

    private function stepsSendSuitMembers(): void
    {
        if (!$this->product->parentProduct->isTypeSuitMembers) {
            return;
        }

        if ($this->product->isActive) {
            return;
        }

        $route = 'courses/recommended/offer/' . $this->product->id;
        $tokenAdmin = config('services.members.token');
        $suitMembersApiService = new SuitMembersApiService(30, $tokenAdmin, 'admin');
        $suitMembersApiService->delete($route);
    }
}
