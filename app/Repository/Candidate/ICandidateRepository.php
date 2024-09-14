<?php
/**
 * Created by PhpStorm.
 * User: aivie
 * Date: 20/7/22
 * Time: 12:10 PM
 */
namespace App\Repository\Candidate;

interface ICandidateRepository
{
    public function createOrUpdate(array $data, $id = null);
}