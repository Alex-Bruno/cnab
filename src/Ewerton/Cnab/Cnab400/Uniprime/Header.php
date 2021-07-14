<?php
/**
 * Created by PhpStorm.
 * User: murilo
 * Date: 14/12/16
 * Time: 13:21
 */

namespace Ewerton\Cnab\Cnab400\Uniprime;


use Ewerton\Cnab\Cnab240\Generico\CnabInterface;
use Ewerton\Cnab\Utils\FormataString;

class Header implements CnabInterface
{

    protected $codigoCedente;
    protected $nomeEmpresa;
    protected $dataGravacao;
    protected $sequencialRemessa;


    /**
     * @return mixed
     */
    public function getCodigoCedente()
    {
        return $this->codigoCedente;
    }

    /**
     * @param mixed $codigoCedente
     * @return Header
     */
    public function setCodigoCedente($codigoCedente)
    {
        $this->codigoCedente = sprintf("%020d", preg_replace('/[[:punct:]]/', '', $codigoCedente));
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomeEmpresa()
    {
        return $this->nomeEmpresa;
    }

    /**
     * @param mixed $nomeEmpresa
     * @return Header
     */
    public function setNomeEmpresa($nomeEmpresa)
    {
        $nomeEmpresaEncoing = FormataString::retiraCaracteresEspecial($nomeEmpresa);
        $this->nomeEmpresa = str_pad(substr(strtoupper($nomeEmpresaEncoing), 0, 30), 30);;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataGravacao()
    {
        return sprintf("%06d", $this->dataGravacao);
    }

    /**
     * @param mixed $dataGravacao
     * @return Header
     */
    public function setDataGravacao(\DateTime $dataGravacao)
    {
        $this->dataGravacao = (int)$dataGravacao->format('dmy');
        return $this;
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
     * @return Header
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
        $linha .= str_pad('COBRANCA', 15, ' ', STR_PAD_RIGHT);
        //pos [27-46]
        $linha .= $this->getCodigoCedente();
        //pos [47-76]
        $linha .= $this->getNomeEmpresa();
        //pos [77-79]
        $linha .= '084';
        //pos [80-94]
        $linha .= str_pad('UNIPRIME', 15, ' ', STR_PAD_RIGHT);
        //pos [95-100]
        $linha .= $this->getDataGravacao();
        //pos [101-108]
        $linha .= str_pad('', 8);
        //pos [109-110]
        $linha .= 'MX';
        //pos [111-117]
        $linha .= $this->getSequencialRemessa();
        //pos [118-394]
        $linha .= str_pad('', 277);
        //pos [396-400]
        $linha .= '000001';

        $linha .= "\r\n";

        return $linha;
    }


}