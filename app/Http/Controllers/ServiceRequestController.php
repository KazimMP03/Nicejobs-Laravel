<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user instanceof \App\Models\Provider) {
            $requests = ServiceRequest::where('provider_id', $user->id)
                ->with('service', 'customUser')
                ->get();

            return view('service_requests.provider.index', compact('requests'));
        }

        if ($user instanceof \App\Models\CustomUser) {
            $requests = ServiceRequest::where('custom_user_id', $user->id)
                ->with('service', 'provider')
                ->get();

            return view('service_requests.custom_user.index', compact('requests'));
        }

        abort(403, 'Acesso não autorizado.');
    }

    public function create(Service $service)
    {
        return view('service_requests.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        $data = $request->validate([
            'message' => 'nullable|string',
        ]);

        ServiceRequest::create([
            'custom_user_id' => auth()->id(),
            'service_id'     => $service->id,
            'provider_id'    => $service->provider_id,
            'status'         => ServiceRequest::STATUS_PENDING,
            'message'        => $data['message'] ?? null,
        ]);

        return redirect()->route('service-requests.index')
                         ->with('success', 'Serviço solicitado com sucesso!');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $this->authorize('view', $serviceRequest);

        $user = auth()->user();

        if ($user instanceof \App\Models\Provider) {
            return view('service_requests_provider.show', compact('serviceRequest'));
        }

        if ($user instanceof \App\Models\CustomUser) {
            return view('service_requests_custom_user.show', compact('serviceRequest'));
        }

        abort(403, 'Acesso não autorizado.');
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if ($user instanceof \App\Models\Provider && $user->id === $serviceRequest->provider_id) {
            $data = $request->validate([
                'status'  => 'required|in:' . implode(',', [
                    ServiceRequest::STATUS_ACCEPTED,
                    ServiceRequest::STATUS_REJECTED,
                ]),
                'message' => 'nullable|string',
            ]);

            $serviceRequest->update($data);

            if ($data['status'] === ServiceRequest::STATUS_ACCEPTED) {
                $exists = Service::where('provider_id', $user->id)
                    ->where('title', $serviceRequest->service->title)
                    ->where('service_category_id', $serviceRequest->service->service_category_id)
                    ->exists();

                if (!$exists) {
                    Service::create([
                        'provider_id'         => $user->id,
                        'service_category_id' => $serviceRequest->service->service_category_id,
                        'title'               => $serviceRequest->service->title,
                        'description'         => $serviceRequest->service->description,
                        'price'               => $serviceRequest->service->price,
                        'status'              => Service::STATUS_ACTIVE,
                    ]);
                }
            }

        } elseif ($user instanceof \App\Models\CustomUser && $user->id === $serviceRequest->custom_user_id) {
            $data = $request->validate([
                'status'  => 'required|in:cancelled',
                'message' => 'nullable|string',
            ]);

            $serviceRequest->update($data);

        } else {
            abort(403, 'Acesso não autorizado.');
        }

        return redirect()->route('service-requests.index')
                         ->with('success', 'Status da solicitação atualizado!');
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $this->authorize('delete', $serviceRequest);

        $serviceRequest->delete();

        return back()->with('success', 'Solicitação removida.');
    }
}
