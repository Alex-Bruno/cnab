<?php
/**
 * Created by PhpStorm.
 * User: murilo
 * Date: 15/12/16
 * Time: 10:28
 */

namespace Ewerton\Cnab\Cnab400\Banrisul;


use Cnab\Remessa\Cnab400\Trailer;
use Ewerton\Cnab\Cnab240\Generico\CnabInterface;

class Trailler implements CnabInterface
{
    private $sequencialRegistro;
    private $somatorioTitulos;
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

    /**
     * @return mixed
     */
    public function getSomatorioTitulos()
    {
        return sprintf("%013d", number_format($this->somatorioTitulos, 2, '', ''));
    }

    /**
     * @param mixed $somatorioTitulos
     * @return Trailler
     */
    public function setSomatorioTitulos($somatorioTitulos)
    {
        $this->somatorioTitulos = $somatorioTitulos;
        return $this;
    }



    public function criaLinha()
    {
        // TODO: Implement criaLinha() method.
        $linha = 9;
        $linha .= str_pad('', 26);
        $linha .= $this->getSomatorioTitulos();
        $linha .= str_pad('', 354);
        $linha .= $this->getSequencialRegistro();

        $linha .= "\r\n";
        return $linha;
    }
}