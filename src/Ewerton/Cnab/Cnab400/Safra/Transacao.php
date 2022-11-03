<?php
/**
 * Created by PhpStorm.
 * User: murilo
 * Date: 14/12/16
 * Time: 18:40
 */

namespace Ewerton\Cnab\Cnab400\Safra;


use Ewerton\Cnab\Cnab240\Generico\CnabInterface;
use Ewerton\Cnab\Utils\FormataString;

class Transacao implements CnabInterface
{

    const CARTEIRA = 1;
    const COD_BANCO = '422';
    const ACEITE = 'N';
    const COD_MORA = '0';
    const OCCURRENCE_CODE = '01';
    const COMPANY_REGISTRATION_TYPE = '02';


    private $cnpj;
    private $codigoCedente;
    private $numeroDocumeto;
    private $nossoNumero;
    private $seuNumero;
    private $vencimento;
    private $valor;
    private $dataEmissao;
    private $valorMoraDia;
    private $tipoInscricaoPagador;
    private $numeroInscricao;
    private $nomePagador;
    private $endereco;
    private $bairro;
    private $cep;
    private $cidade;
    private $estado;
    private $diasMulta;
    private $multa;
    private $sequencialRegistro;
    private $ocorrencia;
    private $dataDesconto = 0;
    private $tipoDesconto = 0;
    private $valorDesconto = 0;
    private $carteira;
    private $agencia;
    private $conta;
    private $conta_dv;
    private $numeroPagador;
    private $tipoDocumento;
    private $dataMuta;
    private $diasProtesto;
    private $sequencialRemessa;

    /**
     * @return mixed
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param mixed $cnpj
     * @return Transacao
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoCedente()
    {
        return $this->codigoCedente;
    }

    /**
     * @param mixed $codigoCedente
     * @return $this
     */
    public function setCodigoCedente($codigoCedente)
    {
        $this->codigoCedente = str_pad(substr($codigoCedente,-9), 9, 0, STR_PAD_LEFT);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroDocumeto()
    {
        return $this->numeroDocumeto;
    }

    /**
     * @param mixed $numeroDocumeto
     * @return Transacao
     */
    public function setNumeroDocumeto($numeroDocumeto)
    {
        $this->numeroDocumeto = $numeroDocumeto;
        $this->nossoNumero = $numeroDocumeto;
        $this->seuNumero = $numeroDocumeto;
        return $this;
    }

    public function getNossoNumero()
    {
        $carteira = str_pad($this->getCarteira(), 2, 0,  STR_PAD_LEFT);
        $numero = $carteira."".str_pad($this->nossoNumero, 11, 0, STR_PAD_LEFT);

        $resto = $this->modulo_11($numero, 7, 1);

        if ($resto == 1) {
            $dv2 = 'P';
        } else if ($resto == 0) {
            $dv2 = 0;
        } else {
            $dv2 = 11 - $resto;
        }

        return sprintf("%010s", $this->nossoNumero . $dv2);
    }

    /**
     * @return mixed
     */
    public function getSeuNumero()
    {
        return $this->seuNumero;
    }

    /**
     * @return mixed
     */
    public function getVencimento()
    {
        return sprintf("%06d", $this->vencimento);
    }

    /**
     * @param \DateTime $vencimento
     * @return $this
     */
    public function setVencimento(\DateTime $vencimento)
    {
        $this->vencimento = (int)$vencimento->format('dmy');
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return sprintf("%013d", number_format($this->valor, 2, '', ''));
    }

    /**
     * @param mixed $valor
     * @return $this
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataEmissao()
    {
        return sprintf("%06d", $this->dataEmissao);
    }

    /**
     * @param \DateTime $dataEmissao
     * @return $this
     */
    public function setDataEmissao(\DateTime $dataEmissao)
    {
        $this->dataEmissao = $dataEmissao->format('dmy');
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValorMoraDia()
    {
        return sprintf("%012d", number_format($this->valorMoraDia, 2, '', ''));
    }

    /**
     * @param float $valor
     * @param float $juros
     * @return $this
     */
    public function setValorMoraDia($valor, $juros)
    {
        $valorJuros = $valor * ($juros / 100);
        $this->valorMoraDia = $valorJuros;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoInscricaoPagador()
    {
        return sprintf("%02d", $this->tipoInscricaoPagador);
    }

    /**
     * @param mixed $tipoInscricaoPagador
     * @return $this
     */
    public function setTipoInscricaoPagador($tipoInscricaoPagador)
    {
        $this->tipoInscricaoPagador = $tipoInscricaoPagador;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroInscricao()
    {
        return sprintf("%014d", preg_replace('/[[:punct:]]/', '', $this->numeroInscricao));
    }

    /**
     * @param mixed $numeroInscricao
     * @return $this
     */
    public function setNumeroInscricao($numeroInscricao)
    {
        $this->numeroInscricao = $numeroInscricao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomePagador()
    {
        return str_pad(strtoupper(substr($this->removerAcentos($this->nomePagador), 0,40)), 40, ' ', STR_PAD_RIGHT);
    }

    /**
     * @param mixed $nomePagador
     * @return $this
     */
    public function setNomePagador($nomePagador)
    {
        $this->nomePagador = $nomePagador;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndereco()
    {
        return str_pad(strtoupper(substr($this->removerAcentos($this->endereco). ' '. $this->removerAcentos($this->bairro), 0,40)), 40, ' ', STR_PAD_RIGHT);
    }

    /**
     * @param mixed $endereco
     * @return $this
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }


    /**
     * @param mixed $bairro
     * @return $this
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCep()
    {
        return  sprintf("%08d", preg_replace('/[[:punct:]]/', '', $this->cep));
    }

    /**
     * @param mixed $cep
     * @return $this
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCidade()
    {
        $cidade = FormataString::retiraCaracteresEspecial($this->cidade);
        return str_pad(strtoupper(substr($cidade, 0,15)), 15, ' ', STR_PAD_RIGHT);
    }

    /**
     * @param mixed $cidade
     * @return $this
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     * @return $this
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiasMulta()
    {
        return sprintf("%02d", $this->diasMulta);
    }

    /**
     * @param \DateTime $vencimento
     * @return $this
     */
    public function setDiasMulta($diasMulta)
    {


        $this->diasMulta = $diasMulta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMulta()
    {
        return sprintf("%04d", number_format($this->multa, 2,'',''));
    }

    /**
     * @param float $multa
     * @return $this
     */
    public function setMulta($multa)
    {
        $this->multa = $multa;
        return $this;
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
     * @return Transacao
     */
    public function setSequencialRegistro($sequencialRegistro)
    {
        $this->sequencialRegistro = $sequencialRegistro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOcorrencia()
    {
        return sprintf("%02d", $this->ocorrencia);
    }

    /**
     * @param mixed $ocorrencia
     * @return Transacao
     */
    public function setOcorrencia($ocorrencia)
    {
        $this->ocorrencia = $ocorrencia;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoDesconto()
    {
        return $this->tipoDesconto;
    }

    /**
     * @param mixed $tipoDesconto
     * @return $this
     */
    public function setTipoDesconto($tipoDesconto)
    {
        $this->tipoDesconto = $tipoDesconto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValorDesconto()
    {
        return sprintf("%013d", number_format($this->valorDesconto, 2,'',''));
    }

    /**
     * @param mixed $valorDesconto
     * @return $this
     */
    public function setValorDesconto($valorDesconto)
    {
        $this->valorDesconto = $valorDesconto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataDesconto()
    {
        return ($this->dataDesconto) ? $this->dataDesconto : '';
    }

    /**
     * @param mixed $dataDesconto
     * @return $this
     */
    public function setDataDesconto(\DateTime $dataDesconto)
    {
        if ($this->valorDesconto > 0){
            $this->dataDesconto = $dataDesconto->format('dmy');
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarteira()
    {
        return $this->carteira;
    }

    /**
     * @param mixed $carteira
     * @return Transacao
     */
    public function setCarteira($carteira)
    {
        $this->carteira = $carteira;
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

    /**
     * @return mixed
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param mixed $conta
     * @return Transacao
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContaDv()
    {
        return $this->conta_dv;
    }

    /**
     * @param mixed $conta_dv
     * @return Transacao
     */
    public function setContaDv($conta_dv)
    {
        $this->conta_dv = $conta_dv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroPagador()
    {
        return $this->numeroPagador;
    }

    /**
     * @param mixed $numeroPagador
     * @return Transacao
     */
    public function setNumeroPagador($numeroPagador)
    {
        $this->numeroPagador = $numeroPagador;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * @param mixed $tipoDocumento
     * @return Transacao
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $listNames = array(
        2 => '01',//'DM - DUPLICATA MERCANTIL ',
        4 => '09',//'DS - DUPLICATA DE SERVICO ',
        //7 => 'LC - LETRA DE CÂMBIO (SOMENTE PARA BANCO 353)',
        //30 => 'LC - LETRA DE CÂMBIO (SOMENTE PARA BANCO 008)',
        12 => '02',//'NP - NOTA PROMISSORIA ',
        //13 => 'NR - NOTA PROMISSORIA RURAL',
        //17 => 'RC - RECIBO',
        //20 => 'AP – APOLICE DE SEGURO',
        //32 => 'BDP – BOLETO DE PROPOSTA',
        //97 => 'CH – CHEQUE',
        //98 => 'ND - NOTA PROMISSORIA DIRETA',
    );

        $this->tipoDocumento = ($tipoDocumento and $listNames[$tipoDocumento]) ? $listNames[$tipoDocumento] : $tipoDocumento;
        return $this;
    }

    /**
     * @param mixed $dataDaMulta
     * @return $this
     */
    public function setDataDaMulta(?\DateTime $dataDaMulta)
    {
        $this->dataDaMulta = ($dataDaMulta) ? $dataDaMulta->format("dmY") : '';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataMuta()
    {
        return $this->dataMuta;
    }

    /**
     * @return mixed
     */
    public function getDiasProtesto()
    {
        return $this->diasProtesto;
    }

    /**
     * @param mixed $diasProtesto
     * @return Transacao
     */
    public function setDiasProtesto($diasProtesto)
    {
        $this->diasProtesto = $diasProtesto;
        return $this;
    }

    public function getTipoCobranca($type = '1') {
        if($this->diasProtesto && $type == '1')
            return $this->diasProtesto;

        if($this->multa && $type == '2')
            return '16';

        return null;
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
     * @return Transacao
     */
    public function setSequencialRemessa($sequencialRemessa)
    {
        $this->sequencialRemessa = $sequencialRemessa;
        return $this;
    }

    public function criaLinha()
    {
        // TODO: Implement criaLinha() method.
        //pos [1-1]
        $linha = 1;
        //pos [2-3]
        $linha .= self::COMPANY_REGISTRATION_TYPE;
        //pos [4-17]
        $linha .= str_pad($this->cnpj, 14, STR_PAD_LEFT);
        //pos [18-22]
        $linha .= str_pad($this->getAgencia(), 5, 0, STR_PAD_LEFT);
        //pos [23-31]
        $linha .= $this->codigoCedente;
        //pos [32-37]
        $linha .= str_pad('', 6, ' ');
        //pos [38-62]
        $linha .= str_pad('', 25, ' ');
        //pos [63-71]
        $linha .= str_pad($this->nossoNumero,  9, 0, STR_PAD_LEFT);
        //pos [72-85]
        $linha .= str_pad('', 14, ' ');
        //pos [86-91]
        $linha .= str_pad($this->dataMuta, 6, ' ', STR_PAD_RIGHT);
        //pos [92-101]
        $linha .= str_pad('', 10, ' ');
        //pos [102-102]
        $linha .= 0;
        //pos [103-104]
        $linha .= '00';
        //pos [105-105]
        $linha .= ' ';
        //pos [106-107]
        $linha .= str_pad($this->diasProtesto, 2, 0, STR_PAD_LEFT);
        //pos [108-108]
        $linha .= self::CARTEIRA;
        //pos [109-110]
        $linha .= self::OCCURRENCE_CODE;
        //pos [111-120]
        $linha .= str_pad($this->nossoNumero,  10, ' ', STR_PAD_RIGHT);
        //pos [121-126]
        $linha .= str_pad($this->getVencimento(), 6, 0, STR_PAD_LEFT);
        //pos [127-139]
        $linha .= str_pad($this->getValor(), 13, 0, STR_PAD_LEFT);
        //pos [140-142]
        $linha .= self::COD_BANCO;
        //pos [143-147]
        $linha .= str_pad($this->agencia, 5, STR_PAD_LEFT);
        //pos [148-149]
        $linha .=  str_pad($this->tipoDocumento, 2, 0, STR_PAD_LEFT);
        //pos [150-150]
        $linha .= self::ACEITE;
        //pos [151-156]
        $linha .= $this->getDataEmissao();
        //pos [157-158]
        $linha .= str_pad($this->getTipoCobranca(), 2, 0);
        //pos [159-160]
        $linha .= str_pad($this->getTipoCobranca('2'), 2, 0);
        //pos [161-173]
        $linha .=  str_pad($this->getValorMoraDia(), 13, 0, STR_PAD_LEFT);
        //pos [174-179]
        $linha .= str_pad($this->getDataDesconto(), 6, 0, STR_PAD_LEFT);
        //pos [180 - 192]
        $linha .= str_pad($this->getValorDesconto(), 13, 0, STR_PAD_LEFT);
        //pos [193 - 205]
        $linha .= str_pad('', 13, 0); /// Valor De Iof Operações De seguro
        //pos [206 - 218]
        $linha .= str_pad('', 13, 0); /// Valor Do Abatimento Concedido Ou Cancelado / Multa
        //pos[219-220]
        $linha .= $this->getTipoInscricaoPagador();
        //pos[221-234]
        $linha .= str_pad($this->getNumeroInscricao(), 14, 0, STR_PAD_LEFT);
        //pos[235-274]
        $linha .= str_pad(substr($this->removerAcentos($this->getNomePagador()),0,40), 40, ' ', STR_PAD_RIGHT);
        //pos[275-314]
        $linha .= str_pad(substr($this->removerAcentos($this->getEndereco()),0,40), 40, ' ', STR_PAD_RIGHT);
        //pos[315-324]
        $linha .= str_pad(substr($this->removerAcentos($this->bairro),0,10), 10, ' ', STR_PAD_RIGHT);
        //pos[325-326]
        $linha .= '  ';
        //pos[327-334]
        $linha .= str_pad($this->onlyNumber($this->getCep()), 8, 0, STR_PAD_LEFT);
        //pos [335-349]
        $linha .= str_pad($this->getCidade(), 15, ' ', STR_PAD_RIGHT);
        //pos [350-351]
        $linha .= str_pad($this->getEstado(), 2, ' ', STR_PAD_RIGHT);
        //pos [352-387]
        $linha .= str_pad('', 36, ' ', STR_PAD_RIGHT);
        //pos [388-388]
        $linha .= $this->getTipoDesconto();
        //pos [389-391]
        $linha .= self::COD_BANCO;
        //pos [392-394]
        $linha .= str_pad($this->sequencialRemessa, 3, 0, STR_PAD_LEFT);
        //pos [395-400]
        $linha .= str_pad($this->sequencialRegistro, 6, 0, STR_PAD_LEFT);;

        $linha .= "\r\n";
        return $linha;
    }

    private function modulo_11($num, $base = 9, $r = 0)
    {
        /**
         *   Autor:
         *           Pablo Costa <pablo@users.sourceforge.net>
         *
         *   Função:
         *    Calculo do Modulo 11 para geracao do digito verificador
         *    de boletos bancarios conforme documentos obtidos
         *    da Febraban - www.febraban.org.br
         *
         *   Entrada:
         *     $num: string num�rica para a qual se deseja calcularo digito verificador;
         *     $base: valor maximo de multiplicacao [2-$base]
         *     $r: quando especificado um devolve somente o resto
         *
         *   Saída:
         *     Retorna o Digito verificador.
         *
         *   Observações:
         *     - Script desenvolvido sem nenhum reaproveitamento de código pré existente.
         *     - Assume-se que a verificação do formato das variáveis de entrada é feita antes da execução deste script.
         */

        $soma = 0;
        $fator = 2;

        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num, $i - 1, 1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2
                $fator = 1;
            }
            $fator++;
        }

        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            if ($digito == 10) {
                $digito = 0;
            }
            return $digito;
        } elseif ($r == 1) {
            if ($soma < 11) {
                return $soma;
            }
            $resto = $soma % 11;
            return $resto;
        }
    }

    function removerAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }

    function onlyNumber($string)
    {
        return preg_replace("/[^A-Za-z0-9]/", "",$string);
    }
}