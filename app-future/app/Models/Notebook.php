<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *  title="Notebook",
 *  description="Notebook model",
 *  @OA\Property(
 *      property="id",
 *      description="ID of current notebook",
 *      type="integer"
 *  ),
 *  @OA\Property(
 *      property="name",
 *      description="Full name of subject in notebook",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="email",
 *      description="Email of subject in notebook",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="phone",
 *      description="Phone of subject in notebook",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="birth_date",
 *      description="Birth date of subject in notebook",
 *      default=null,
 *      type="date"
 *  ),
 *  @OA\Property(
 *      property="company",
 *      description="Company of subject in notebook",
 *      default=null,
 *      type="string"
 *  )
 * )
 */
class Notebook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'phone', 'birth_date', 'company', 'deleted',];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted',];
}
