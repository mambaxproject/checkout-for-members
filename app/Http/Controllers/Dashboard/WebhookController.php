<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\{StoreWebhookRequest, UpdateWebhookRequest};
use App\Models\Webhook;
use Illuminate\Http\RedirectResponse;

class WebhookController extends Controller
{
    public function store(StoreWebhookRequest $request): RedirectResponse
    {
        $webhook = user()->shop()->webHooks()->create($request->validated());

        $webhook->events()->sync($request->validated('event_id'));

        $webhook->products()->sync($request->validated('product_id'));

        return back()->with('success', 'Webhook criado com sucesso');
    }

    public function update(UpdateWebhookRequest $request, Webhook $webhook): RedirectResponse
    {
        $data = $request->validated();
        $webhook->fill($data);
        $webhook->save();

        $event_id = $data['event_id'] ?? [];

        if (count($event_id)) {
            $webhook->events()->sync($event_id);
        }

        return back()->with('success', 'Webhook atualizado com sucesso');
    }

    public function destroy(Webhook $webhook): RedirectResponse
    {
        $webhook->delete();

        return back()->with('success', 'Webhook deletado com sucesso');
    }

}
