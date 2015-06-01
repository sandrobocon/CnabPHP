<?php
namespace Cnab\Retorno\Cnab240;

class HeaderArquivo extends \Cnab\Format\Linha
{
	public function __construct($codigo_banco)
    {
        $yamlLoad = new \Cnab\Format\YamlLoad($codigo_banco);
        $yamlLoad->load($this, 'cnab240', 'header_arquivo');
	}

	public function getConta()
    {
        if($this->existField('conta'))
            return $this->conta;
        else
            return null;
    }

    public function getContaDac()
    {
        if($this->existField('conta_dv'))
            return $this->conta_dv;
        else
            return null;
    }

    public function getCodigoConvenio() {
        if ($this->existField('codigo_convenio'))
            return $this->codigo_convenio;
        else
            return null;
    }

    public function getCodigoCedente()
    {
        if ($this->existField('codigo_cedente')) {
            //  Para CAIXA, identifica 2 padrões para retorno com e sem cod operação junto com o campo de codigo_cedente
            if ($this->codigo_banco == 104) {
                $codigo_cedente = str_pad($this->codigo_cedente, 12, '0', STR_PAD_LEFT);
                // Padrão com cod operação  0OOOCCCCCCCC
                if (substr($codigo_cedente, 1,1) == 0 and substr($codigo_cedente, 2,3) > 0 and substr($codigo_cedente, -6) > 0) {
                    return substr($codigo_cedente, -8);
                // Padrão sem cod operação e codigo_cedente no inicio  CCCCCD000000
                } elseif (substr($codigo_cedente, 1,4) > 0 and substr($codigo_cedente, -6) == 0) {
                    return str_pad(substr($codigo_cedente, 0,5), 8, '0', STR_PAD_LEFT);
                // Caso contrario consideramos padrão sem cod operação 'padrão' 0000CCCCCCCC
                } else {
                    return $codigo_cedente;
                }
            } else {
                return $this->codigo_cedente;
            }
        } else {
            return null;
        }
    }

    public function getCodigoCedenteDv()
    {
        var_dump($this->codigo_cedente_dv);
        if ($this->existField('codigo_cedente_dv')) {
            //  Para CAIXA, identifica 2 padrões para retorno com e sem cod operação junto com o campo de codigo_cedente
            if ($this->codigo_banco == 104) {
                $codigo_cedente = str_pad($this->codigo_cedente, 12, '0', STR_PAD_LEFT);
                // Padrão sem cod operação e codigo_cedente no inicio  CCCCCD000000
                if (substr($codigo_cedente, 1,4) > 0 and substr($codigo_cedente, -6) == 0) {
                    return substr($codigo_cedente, 5,1);
                // Caso contrario consideramos padrão generic
                } else {
                    return $this->codigo_cedente_dv;
                }
            } else {
                return $this->codigo_cedente_dv;
            }
        } else {
            return null;
        }
    }
}