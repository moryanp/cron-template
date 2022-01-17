<?php

namespace App\Model\Entity;

final class Cnpj extends Consult
{

    /**
     * @var String
     * cnpj da empresa
     */
    private $cnpj;

    /**
     * @var int
     * index utilizado para consultas no banco de dados
     */
    private $cnpjIndex;

    /**
     * @var String|null
     * razao social da empresa
     */
    private $razaoSocial;

    /**
     * @var String|null
     * email da empresa
     */
    private $email;

    /**
     * @var String|null
     * data de abertura da empresa
     */
    private $dataAbertura;

    /**
     * @var String|null
     * nome fantasia da empresa
     */
    private $nomeFantasia;

    /**
     * @var String|null
     * telefone da empresa
     */
    private $telefone;

    /**
     * @var String|null
     * cnae (cadastro nacional de atividades economicas)
     */
    private $cnae;

    /**
     * @var String|null
     * natureza juridica (regime juridico)
     */
    private $naturezaJuridica;

    /**
     * @var String|null
     * porte da empresa
     */
    private $porte;

    /**
     * @var String|null
     * estado
     */
    private $estado;

    /**
     * @var String|null
     * cidade
     */
    private $cidade;

    /**
     * @var String|null
     * bairro
     */
    private $bairro;

    /**
     * @var String|null
     * logradouro
     */
    private $logradouro;

    /**
     * @var String|null
     * numero do endereco
     */
    private $numero;

    /**
     * @var String|null
     * complemento do endereco
     */
    private $complemento;

    /**
     * @var String|null
     * cep do endereco
     */
    private $cep;


    public function __construct($id, $statusConsulta, $statusDocumento, $indicadorFraude, $executionId, $dataAtualizacao, $cnpj, $razaoSocial, $email, $dataAbertura, $nomeFantasia, $telefone, $cnae, $naturezaJuridica, $porte, $estado, $cidade, $bairro, $logradouro, $numero, $complemento, $cep)
    {
        parent::__construct($id, $statusConsulta, $statusDocumento, $indicadorFraude, $executionId, $dataAtualizacao);
        $this->cnpj = $cnpj;
        $this->razaoSocial = $razaoSocial;
        $this->email = $email;
        $this->dataAbertura = $dataAbertura;
        $this->nomeFantasia = $nomeFantasia;
        $this->telefone = $telefone;
        $this->cnae = $cnae;
        $this->naturezaJuridica = $naturezaJuridica;
        $this->porte = $porte;
        $this->estado = $estado;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->logradouro = $logradouro;
        $this->numero = $numero;
        $this->complemento = $complemento;
        $this->cep = $cep;
    }

    /**
     * Get cnpj da empresa
     *
     * @return  String
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Set cnpj da empresa
     *
     * @param  String  $cnpj  cnpj da empresa
     *
     * @return  self
     */
    public function setCnpj(String $cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Get index utilizado para consultas no banco de dados
     *
     * @return  int
     */
    public function getCnpjIndex()
    {
        return $this->cnpjIndex;
    }

    /**
     * Set index utilizado para consultas no banco de dados
     *
     * @param  int  $cnpjIndex  index utilizado para consultas no banco de dados
     *
     * @return  self
     */
    public function setCnpjIndex(int $cnpjIndex)
    {
        $this->cnpjIndex = $cnpjIndex;

        return $this;
    }

    /**
     * Get razao social da empresa
     *
     * @return  String
     */
    public function getRazaoSocial()
    {
        return $this->razaoSocial;
    }

    /**
     * Set razao social da empresa
     *
     * @param  String  $razaoSocial  razao social da empresa
     *
     * @return  self
     */
    public function setRazaoSocial(String $razaoSocial)
    {
        $this->razaoSocial = $razaoSocial;

        return $this;
    }

    /**
     * Get email da empresa
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email da empresa
     *
     * @param  string  $email  email da empresa
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get data de abertura da empresa
     *
     * @return  String
     */
    public function getDataAbertura()
    {
        return $this->dataAbertura;
    }

    /**
     * Set data de abertura da empresa
     *
     * @param  String  $dataAbertura  data de abertura da empresa
     *
     * @return  self
     */
    public function setDataAbertura(String $dataAbertura)
    {
        $this->dataAbertura = $dataAbertura;

        return $this;
    }

    /**
     * Get nome fantasia da empresa
     *
     * @return  String
     */
    public function getNomeFantasia()
    {
        return $this->nomeFantasia;
    }

    /**
     * Set nome fantasia da empresa
     *
     * @param  String  $nomeFantasia  nome fantasia da empresa
     *
     * @return  self
     */
    public function setNomeFantasia(String $nomeFantasia)
    {
        $this->nomeFantasia = $nomeFantasia;

        return $this;
    }

    /**
     * Get telefone da empresa
     *
     * @return  String
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set telefone da empresa
     *
     * @param  String  $telefone  telefone da empresa
     *
     * @return  self
     */
    public function setTelefone(String $telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get cnae (cadastro nacional de atividades economicas)
     *
     * @return  String
     */
    public function getCnae()
    {
        return $this->cnae;
    }

    /**
     * Set cnae (cadastro nacional de atividades economicas)
     *
     * @param  String  $cnae  cnae (cadastro nacional de atividades economicas)
     *
     * @return  self
     */
    public function setCnae(String $cnae)
    {
        $this->cnae = $cnae;

        return $this;
    }

    /**
     * Get natureza juridica (regime juridico)
     *
     * @return  String
     */
    public function getNaturezaJuridica()
    {
        return $this->naturezaJuridica;
    }

    /**
     * Set natureza juridica (regime juridico)
     *
     * @param  String  $naturezaJuridica  natureza juridica (regime juridico)
     *
     * @return  self
     */
    public function setNaturezaJuridica(String $naturezaJuridica)
    {
        $this->naturezaJuridica = $naturezaJuridica;

        return $this;
    }

    /**
     * Get porte da empresa
     *
     * @return  String
     */
    public function getPorte()
    {
        return $this->porte;
    }

    /**
     * Set porte da empresa
     *
     * @param  String  $porte  porte da empresa
     *
     * @return  self
     */
    public function setPorte(String $porte)
    {
        $this->porte = $porte;

        return $this;
    }

    /**
     * Get estado
     *
     * @return  String
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set estado
     *
     * @param  String  $estado  estado
     *
     * @return  self
     */
    public function setEstado(String $estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get cidade
     *
     * @return  String
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * Set cidade
     *
     * @param  String  $cidade  cidade
     *
     * @return  self
     */
    public function setCidade(String $cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * Get bairro
     *
     * @return  String
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * Set bairro
     *
     * @param  String  $bairro  bairro
     *
     * @return  self
     */
    public function setBairro(String $bairro)
    {
        $this->bairro = $bairro;

        return $this;
    }

    /**
     * Get logradouro
     *
     * @return  String
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * Set logradouro
     *
     * @param  String  $logradouro  logradouro
     *
     * @return  self
     */
    public function setLogradouro(String $logradouro)
    {
        $this->logradouro = $logradouro;

        return $this;
    }

    /**
     * Get numero do endereco
     *
     * @return  String
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set numero do endereco
     *
     * @param  String  $numero  numero do endereco
     *
     * @return  self
     */
    public function setNumero(String $numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get complemento do endereco
     *
     * @return  String
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Set complemento do endereco
     *
     * @param  String  $complemento  complemento do endereco
     *
     * @return  self
     */
    public function setComplemento(String $complemento)
    {
        $this->complemento = $complemento;

        return $this;
    }

    /**
     * Get cep do endereco
     *
     * @return  String
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Set cep do endereco
     *
     * @param  String  $cep  cep do endereco
     *
     * @return  self
     */
    public function setCep(String $cep)
    {
        $this->cep = $cep;

        return $this;
    }
}
