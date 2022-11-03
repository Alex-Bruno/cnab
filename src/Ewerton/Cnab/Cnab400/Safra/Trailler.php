<?php
/**
 * Created by PhpStorm.
 * User: murilo
 * Date: 15/12/16
 * Time: 10:28
 */

namespace Ewerton\Cnab\Cnab400\Safra;


use Cnab\Remessa\Cnab400\Trailer;
use Ewerton\Cnab\Cnab240\Generico\CnabInterface;

class Trailler implements CnabInterface
{
    private $sequencialRegistro;
    private $valorTotal;
    private $quantidadeParcelas;
    private $sequencia = '001';

    /**
     * @return mixed
     */
    public function getSequencia()
    {
        return $this->sequencia;
    }

    /**
     * @param mixed $sequencia
     */
    public function setSequencia($sequencia): void
    {
        $this->sequencia = $sequencia;
    }

    /**
     * @return mixed
     */
    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    /**
     * @param mixed $valorTotal
     */
    public function setValorTotal($valorTotal): void
    {
        $this->valorTotal = $valorTotal;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeParcelas()
    {
        return $this->quantidadeParcelas;
    }

    /**
     * @param mixed $quantidadeParcelas
     */
    public function setQuantidadeParcelas($quantidadeParcelas): void
    {
        $this->quantidadeParcelas = $quantidadeParcelas;
    }

    /**
     * @return mixed
     */
    public function getSequencialRegistro()
    {
        return sprintf("%06d",$this->sequencialRegistro);
    }

    /**
     * @param mixed $sequencialRegistro
     * @return $this
     */
    public function setSequencialRegistro($sequencialRegistro)
    {
        $this->sequencialRegistro = $sequencialRegistro;
        return $this;
    }

    public function criaLinha()
    {
        // TODO: Implement criaLinha() method.
        $linha = 9;
        $linha .= str_pad('', 367);
        //[369-376]
        $linha .= str_pad($this->onlyNumber($this->getQuantidadeParcelas()), 8, 0, STR_PAD_LEFT);
        //[369-376]
        $linha .= str_pad($this->onlyNumber($this->getValorTotal()), 15, 0, STR_PAD_LEFT);
        $linha = str_pad($linha, 391, ' ', STR_PAD_RIGHT);
        //pos [392-394]
        $linha .= str_pad($this->getSequencia(), 3, 0, STR_PAD_LEFT);
        $linha .= $this->getSequencialRegistro();

        $linha .= "\r\n";
        return $linha;
    }
    function onlyNumber($string)
    {
        return preg_replace("/[^A-Za-z0-9]/", "",$string);
    }
}