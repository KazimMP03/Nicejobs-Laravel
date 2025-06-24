<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    // === Status possíveis === //
    public const STATUS_REQUESTED         = 'requested';
    public const STATUS_CHAT_OPENED       = 'chat_opened';
    public const STATUS_PENDING_ACCEPT    = 'pending_acceptance';
    public const STATUS_ACCEPTED          = 'accepted';
    public const STATUS_REJECTED          = 'rejected';
    public const STATUS_CANCELLED         = 'cancelled';
    public const STATUS_COMPLETED         = 'completed';

    protected $fillable = [
        'custom_user_id',
        'provider_id',
        'address_id',
        'description',
        'initial_budget',
        'final_price',
        'status',
        'provider_accepted',
        'customer_accepted',
        'service_date',
    ];

    protected $casts = [
        'service_date' => 'datetime',
        'initial_budget'      => 'decimal:2',
        'final_price'         => 'decimal:2',
        'provider_accepted'   => 'boolean',
        'customer_accepted'   => 'boolean',
        'status'              => 'string',
    ];

    // === Relações === //

    /**
     * Muitos-para-um com CustomUser
     */
    public function customUser()
    {
        return $this->belongsTo(CustomUser::class);
    }

    /**
     * Muitos-para-um com Provider
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Muitos-para-um com Address
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Um-para-um com Chat
     */
    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    // === Métodos auxiliares de status === //

    public function isRequested(): bool
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    public function isChatOpened(): bool
    {
        return $this->status === self::STATUS_CHAT_OPENED;
    }

    public function isPendingAcceptance(): bool
    {
        return $this->status === self::STATUS_PENDING_ACCEPT;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFinalized(): bool
    {
        return in_array($this->status, [
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_REJECTED,
        ]);
    }

    // === Verificações de ações === //

    public function canPropose(): bool
    {
        return in_array($this->status, [
            self::STATUS_REQUESTED,
            self::STATUS_CHAT_OPENED,
            self::STATUS_PENDING_ACCEPT,
        ]);
    }

    public function canAcceptProposal(): bool
    {
        return $this->status === self::STATUS_PENDING_ACCEPT
            && !$this->customer_accepted;
    }

    public function canRejectProposal(): bool
    {
        return $this->status === self::STATUS_PENDING_ACCEPT;
    }

    public function canAccept(): bool
    {
        return in_array($this->status, [
            self::STATUS_REQUESTED,
            self::STATUS_CHAT_OPENED,
        ]);
    }

    public function canCancel(): bool
    {
        return in_array($this->status, [
            self::STATUS_REQUESTED,
            self::STATUS_CHAT_OPENED,
            self::STATUS_PENDING_ACCEPT,
            self::STATUS_ACCEPTED,
        ]);
    }

    public function canComplete(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function canReject(): bool
    {
        return in_array($this->status, [
            self::STATUS_REQUESTED,
            self::STATUS_CHAT_OPENED,
        ]);
    }

    // === Ações de negociação === //

    /**
     * Tenta avançar para "accepted" se ambos aceitaram.
     */
    public function trySetAccepted()
    {
        if ($this->provider_accepted && $this->customer_accepted) {
            $this->status = self::STATUS_ACCEPTED;
            $this->save();
        }
    }

    /**
     * Reseta a proposta, volta para chat_opened.
     */
    public function resetProposal()
    {
        $this->update([
            'service_date' => null,
            'final_price'        => null,
            'provider_accepted'  => false,
            'customer_accepted'  => false,
            'status'             => self::STATUS_CHAT_OPENED,
        ]);
    }

    // === Método auxiliar para traduzir os status === //
    public function getStatusLabel()
    {
        return match ($this->status) {
            'requested'       => 'Solicitado',
            'chat_opened'     => 'Chat Aberto',
            'pending_acceptance' => 'Pendente de Aceitação',
            'accepted'        => 'Aceito',
            'completed'       => 'Concluído',
            'cancelled'       => 'Cancelado',
            'rejected'        => 'Rejeitado',
            default            => ucfirst($this->status),
        };
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_REQUESTED      => 'Solicitado',
            self::STATUS_CHAT_OPENED    => 'Chat Aberto',
            self::STATUS_PENDING_ACCEPT => 'Pendente de Aceitação',
            self::STATUS_ACCEPTED       => 'Aceito',
            self::STATUS_COMPLETED      => 'Concluído',
            self::STATUS_CANCELLED      => 'Cancelado',
            self::STATUS_REJECTED       => 'Rejeitado',
        ];
    }

    /**
     * Retorna array de status na ordem desejada para listagem.
     */
    public static function statusOrder(): array
    {
        return [
            self::STATUS_REQUESTED,         // solicitado
            self::STATUS_CHAT_OPENED,       // chat aberto
            self::STATUS_PENDING_ACCEPT,    // pendente de aceitação
            self::STATUS_ACCEPTED,          // aceito
            // talvez concluir antes de cancelado/rejeitado, depende do fluxo
            self::STATUS_COMPLETED,         // concluído
            self::STATUS_CANCELLED,         // cancelado
            self::STATUS_REJECTED,          // rejeitado
        ];
    }

    // === Método auxiliar para avaliação de usuário === //
    public function wasReviewedBy($user)
    {
        return \App\Models\Review::wasReviewedBy(
            $user->id,
            $user instanceof \App\Models\Provider ? 'provider' : 'custom_user',
            $user instanceof \App\Models\CustomUser ? $this->provider_id : null,
            $user instanceof \App\Models\Provider ? $this->custom_user_id : null
        );
    }


}
