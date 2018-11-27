<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionLogRepository")
 * @ORM\Table(
 *     name="transaction_log",
 *     indexes={
 *         @ORM\Index(name="transaction_idx", columns={"transaction_id"}),
 *     }
 * )
 */
class TransactionLogRecord
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $transaction_id;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private $transaction_status_code;

    /**
     * @ORM\Column(type="string")
     */
    private $summary;

    /**
     * @ORM\Column(type="datetime", length=6)
     */
    private $transacted_at;
}
