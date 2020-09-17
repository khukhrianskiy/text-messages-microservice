<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    id
 * @property string body
 * @property string status
 * @property string phone_number
 */
class TextMessage extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_DELIVERED = 'delivered';

    /**
     * @var string[]
     */
    protected $fillable = [
        'body',
        'status',
        'phone_number',
    ];
}
