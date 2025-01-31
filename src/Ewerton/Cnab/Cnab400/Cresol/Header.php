<?php

namespace Ewerton\Cnab\Cnab400\Cresol;

use Ewerton\Cnab\Cnab400\Generico\HeaderGenerico;

class Header extends HeaderGenerico
{
    private $cobranca = 'COBRANCA';
    private $sequencialRemessa;

    public function getCobranca()
    {
        return sprintf("%-15s", $this->cobranca);
    }

    public function getCodigoCedente()
    {
        return sprintf("%020d", $this->codigoCedente);
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
        $this->sequencialRemessa = sprintf("%07d", $sequencialRemessa);
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
        //pos [27-46]
        $linha .= $this->getCodigoCedente();
        //pos [47-76]
        $linha .= $this->getNomeEmpresa();
        //pos [77-94]
        $linha .= str_pad('133Cresol', 18, ' ', STR_PAD_RIGHT);
        //pos [95-100]
        $linha .= $this->getDataGravacao();
        //pos [101-108]
        $linha .= str_pad('', 8);
        //pos [109-110]
        $linha .= str_pad('', 2);
        //pos [111-117]
        $linha .= str_pad('', 7);
        //pos [118-394]
        $linha .= str_pad('', 277);
        #$linha .= $this->getSequencialRemessa();
        //pos [395-400]
        $linha .= '000001';

        $linha .= "\r\n";

        return $linha;
    }

}