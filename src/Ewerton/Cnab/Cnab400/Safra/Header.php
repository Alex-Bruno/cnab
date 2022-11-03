<?php

namespace Ewerton\Cnab\Cnab400\Safra;

use Ewerton\Cnab\Cnab400\Generico\HeaderGenerico;

class Header extends HeaderGenerico
{
    private $cobranca = 'COBRANCA';
    private $sequencialRemessa;
    private $agencia;

    public function getCobranca()
    {
        return sprintf("%-15s", $this->cobranca);
    }

    public function getCodigoCedente()
    {
        return sprintf("%014d", $this->codigoCedente);
    }

    /**
     * @return mixed
     */
    public function getSequencialRemessa()
    {
        return $this->sequencialRemessa;
    }

    /**
     * @param mixed $sequencialRemessa
     * @return \Ewerton\Cnab\Cnab400\Bradesco\Header
     */
    public function setSequencialRemessa($sequencialRemessa)
    {
        $this->sequencialRemessa = sprintf("%03d", $sequencialRemessa);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * @param mixed $agencia
     * @return Transacao
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    public function criaLinha()
    {
        // TODO: Implement criaLinha() method.
        //pos [1-9]
        $linha = '01REMESSA';
        //pos [10-11]
        $linha .= '01';
        //pos [12-26]
        $linha .= $this->getCobranca();
        //pos [27-32]
        $linha .= str_pad($this->getAgencia(), 5, 0, STR_PAD_LEFT);
        //pos [33-40]
        $linha .= str_pad(substr($this->getCodigoCedente(),-9), 9, 0, STR_PAD_LEFT);
        //pos [41-46]
        $linha .= str_pad('', 6, ' ');
        //pos [47-76]
        $linha .= $this->getNomeEmpresa();
        //pos [77-94]
        $linha .= str_pad('422BANCO SAFRA', 18, ' ', STR_PAD_RIGHT);
        //pos [95-100]
        $linha .= $this->getDataGravacao();
        //pos [101-391]
        $linha .= str_pad('', 291, ' ');
        //pos [392-394]
        $linha .= $this->getSequencialRemessa();
        //pos [395-400]
        $linha .= '000001';

        $linha .= "\r\n";

        return $linha;
    }

}