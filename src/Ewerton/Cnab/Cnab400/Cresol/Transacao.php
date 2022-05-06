<?php
/**
 * Created by PhpStorm.
 * User: murilo
 * Date: 14/12/16
 * Time: 18:40
 */

namespace Ewerton\Cnab\Cnab400\Cresol;


use Ewerton\Cnab\Cnab240\Generico\CnabInterface;
use Ewerton\Cnab\Utils\FormataString;

class Transacao implements CnabInterface
{

    const CARTEIRA = 1;
    const COD_BANCO = '133';
    const ACEITE = 'N';
    const COD_MORA = '0';

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
    private $valorDesconto = 0;
    private $carteira;
    private $agencia;
    private $conta;
    private $conta_dv;
    private $numeroPagador;
    private $tipoDocumento;

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
        $this->codigoCedente = sprintf("%013d", preg_replace('/[[:punct:]]/', '', $codigoCedente));
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

        return sprintf("%010d", $this->nossoNumero . $dv2);
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
        return sprintf("%06d", $this->dataDesconto);
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
        $this->tipoDocumento = $tipoDocumento;
        return $this;
    }

    public function criaLinha()
    {
        // TODO: Implement criaLinha() method.
        //pos [1-1]
        $linha = 1;
        //pos [2-20]
        $linha .= str_pad('', 19, ' ');
        //pos [21-21]
        $linha .= 0;
        //pos [22-24]
        $linha .= str_pad($this->getCarteira(), 3, 0, STR_PAD_LEFT);
        //pos [25-29]
        $linha .= str_pad($this->getAgencia(), 5, 0, STR_PAD_LEFT);
        //pos [30-36]
        $linha .= str_pad($this->getConta(), 7, 0, STR_PAD_LEFT);
        //pos [37-37]
        $linha .= str_pad($this->getContaDv(), 1, 0, STR_PAD_LEFT);
        //pos [38-62]
        $linha .= str_pad($this->getNumeroDocumeto(),  25, 0, STR_PAD_LEFT);
        //pos[63-65]
        $linha .= str_pad('', 3, ' ');
        //pos[66-66]
        $linha .= ((int)$this->getMulta() > 0) ? 2 : 0;
        //pos[67-70]
        $linha .= str_pad($this->getMulta(),  4, 0, STR_PAD_LEFT);
        //pos[71-82]
        $linha .= str_pad($this->getNossoNumero(),  12, 0, STR_PAD_LEFT);
        //pos[83-92]
        $linha .= str_pad('', 10, ' ');
        //pos[93-93]
        $linha .= str_pad('1', 1,  0, STR_PAD_BOTH);
        //pos[94-108]
        $linha .= str_pad('', 15, ' ');
        //pos [109-110]
        $linha .= str_pad($this->getOcorrencia(),  2, 0, STR_PAD_LEFT);
        //pos [111-120]
        $linha .= str_pad($this->getSeuNumero(), 10, ' ', STR_PAD_RIGHT);
        //pos [121-126]
        $linha .= str_pad($this->getVencimento(), 6, 0, STR_PAD_LEFT);
        //pos [127-139]
        $linha .= str_pad($this->getValor(), 13, 0, STR_PAD_LEFT);
        //pos [140-142]
        $linha .= str_pad('', 3, ' ');
        //pos [143-147]
        $linha .= str_pad('', 5, ' ');
        //pos [148-149]
        $linha .=  str_pad($this->tipoDocumento, 2, 0, STR_PAD_LEFT);
        //pos [150-150]
        $linha .= str_pad('', 1, ' ');
        //pos [151-156]
        $linha .= $this->getDataEmissao();
        //pos [157-158]
        $linha .= str_pad('', 2, ' ');
        //pos [159-160]
        $linha .= str_pad('', 2, ' ');
        //pos [161-173]
        $linha .=  str_pad($this->getValorMoraDia(), 13, 0, STR_PAD_LEFT);
        //pos [174-179]
        $linha .= str_pad($this->getDataDesconto(), 6, 0, STR_PAD_LEFT);
        //pos [180 - 192]
        $linha .= str_pad($this->getValorDesconto(), 13, 0, STR_PAD_LEFT);
        //pos [193 - 218]
        $linha .= str_pad('', 26, 0);
        //pos[219-220]
        $linha .= $this->getTipoInscricaoPagador();
        //pos[221-234]
        $linha .= str_pad($this->getNumeroInscricao(), 14, 0, STR_PAD_LEFT);
        //pos[235-274]
        $linha .= str_pad($this->getNomePagador(), 40, ' ', STR_PAD_RIGHT);
        //pos[275-314]
        $linha .= str_pad($this->getEndereco(), 40, ' ', STR_PAD_RIGHT);
        //pos[315-326]
        $linha .= str_pad('', 12, ' ');
        //pos[327-334]
        $linha .= $this->getCep();
        //pos [335-394]
        $linha .= str_pad('', 60, ' ');
        //pos [395-400]
        $linha .= $this->getSequencialRegistro();
        
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
}
