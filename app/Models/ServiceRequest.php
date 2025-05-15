<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    // Status possíveis
    public const STATUS_REQUESTED    = 'requested';
    public const STATUS_CHAT_OPENED  = 'chat_opened';
    public const STATUS_ACCEPTED     = 'accepted';
    public const STATUS_REJECTED     = 'rejected';  
    public const STATUS_CANCELLED    = 'cancelled';
    public const STATUS_COMPLETED    = 'completed';

    protected $fillable = [
        'custom_user_id',
        'provider_id',
        'address_id',
        'description',
        'initial_budget',
        'final_price',
        'status',
    ];

    protected $casts = [
        'initial_budget' => 'decimal:2',
        'final_price'    => 'decimal:2',
        'status'         => 'string',
    ];

    // === Métodos auxiliares de status ===
    public function isRequested(): bool
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    public function isChatOpened(): bool
    {
        return $this->status === self::STATUS_CHAT_OPENED;
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

    /**
     * Relação de muitos-para-um com CustomUser
     */
    public function customUser()
    {
        return $this->belongsTo(CustomUser::class);
    }

    /**
     * Relação de muitos-para-um com Providers
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Relação de muitos-para-um com Address
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
