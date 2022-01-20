<?php

namespace App\Model\Entity;

final class CpfModel extends ConsultModel
{

    /**
     * @var String
     * nome completo
     */
    private $nome;

    /**
     * @var String
     * cpf
     */
    private $cpf;

    /**
     * @var int
     * index utilizado para consultas no banco de dados
     */
    private $cpfIndex;

    /**
     * @var String
     * data de nascimento
     */
    private $dataNascimento;

    /**
     * @var String|null
     * ano de obito
     */
    private $anoObito;

    /**
     * @var int|null
     * flag indicando se esta ou nao em obtio
     */
    private $indicadorObito;


    public function __construct($id, $statusConsulta, $indicadorFraude, $executionId, $dataAtualizacao, $nome, $cpf, $cpfIndex, $dataNascimento, $anoObito, $indicadorObito)
    {
        parent::__construct($id, $statusConsulta, $indicadorFraude, $executionId, $dataAtualizacao);
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->cpfIndex = $cpfIndex;
        $this->dataNascimento = $dataNascimento;
        $this->anoObito = $anoObito;
        $this->indicadorObito = $indicadorObito;
    }

    /**
     * Get nome completo
     *
     * @return  String
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set nome completo
     *
     * @param  String  $nome  nome completo
     *
     * @return  self
     */
    public function setNome(String $nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return  String
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set cpf
     *
     * @param  String  $cpf  cpf
     *
     * @return  self
     */
    public function setCpf(String $cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get index utilizado para consultas no banco de dados
     *
     * @return  int
     */
    public function getCpfIndex()
    {
        return $this->cpfIndex;
    }

    /**
     * Set index utilizado para consultas no banco de dados
     *
     * @param  int  $cpfIndex  index utilizado para consultas no banco de dados
     *
     * @return  self
     */
    public function setCpfIndex(int $cpfIndex)
    {
        $this->cpfIndex = $cpfIndex;

        return $this;
    }

    /**
     * Get data de nascimento
     *
     * @return  String
     */
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * Set data de nascimento
     *
     * @param  String  $dataNascimento  data de nascimento
     *
     * @return  self
     */
    public function setDataNascimento(String $dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;

        return $this;
    }

    /**
     * Get ano de obito
     *
     * @return  String
     */
    public function getAnoObito()
    {
        return $this->anoObito;
    }

    /**
     * Set ano de obito
     *
     * @param  String  $anoObito  ano de obito
     *
     * @return  self
     */
    public function setAnoObito(String $anoObito)
    {
        $this->anoObito = $anoObito;

        return $this;
    }

    /**
     * Get flag indicando se esta ou nao em obtio
     *
     * @return  int
     */
    public function getIndicadorObito()
    {
        return $this->indicadorObito;
    }

    /**
     * Set flag indicando se esta ou nao em obtio
     *
     * @param  int  $indicadorObito  flag indicando se esta ou nao em obtio
     *
     * @return  self
     */
    public function setIndicadorObito(int $indicadorObito)
    {
        $this->indicadorObito = $indicadorObito;

        return $this;
    }
}
