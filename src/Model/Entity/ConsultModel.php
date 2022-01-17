<?php

namespace App\Model\Entity;

abstract class Consult
{

    /**
     * @var int
     * id da consulta
     */
    protected $id;

    /**
     * @var int|NULL
     * status da consulta (0 - processando, 1 - pendente, 2 - pendente ocr, 3 - reprovado e 4 - aprovado)
     */
    protected $statusConsulta;

    /**
     * @var int|NULL
     * status do documento (0- aprovado e 1- reprovado)
     */
    protected $statusDocumento;

    /**
     * @var int|NULL
     * status de fraude do documento (0- false, 1- true)
     */
    protected $indicadorFraude;

    /**
     * @var String|NULL
     * id unica da execucao do tipo cnpj
     */
    protected $executionId;

    /**
     * @var String|NULL
     * data da ultima atualizacao do documento
     */
    protected $dataAtualizacao;


    public function __construct($id, $statusConsulta, $statusDocumento, $indicadorFraude, $executionId, $dataAtualizacao)
    {
        $this->id = $id;
        $this->statusConsulta = $statusConsulta;
        $this->statusDocumento = $statusDocumento;
        $this->indicadorFraude = $indicadorFraude;
        $this->executionId = $executionId;
        $this->dataAtualizacao = $dataAtualizacao;
    }

    /**
     * Get id da consulta
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id da consulta
     *
     * @param  int  $id  id da consulta
     *
     * @return  self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get status da consulta (0 - processando, 1 - pendente, 2 - pendente ocr, 3 - reprovado e 4 - aprovado)
     *
     * @return  int
     */
    public function getStatusConsulta()
    {
        return $this->statusConsulta;
    }

    /**
     * Set status da consulta (0 - processando, 1 - pendente, 2 - pendente ocr, 3 - reprovado e 4 - aprovado)
     *
     * @param  int  $statusConsulta  status da consulta (0 - processando, 1 - pendente, 2 - pendente ocr, 3 - reprovado e 4 - aprovado)
     *
     * @return  self
     */
    public function setStatusConsulta(int $statusConsulta)
    {
        $this->statusConsulta = $statusConsulta;

        return $this;
    }

    /**
     * Get status do documento (0- aprovado e 1- reprovado)
     *
     * @return  int
     */
    public function getStatusDocumento()
    {
        return $this->statusDocumento;
    }

    /**
     * Set status do documento (0- aprovado e 1- reprovado)
     *
     * @param  int  $statusDocumento  status do documento (0- aprovado e 1- reprovado)
     *
     * @return  self
     */
    public function setStatusDocumento(int $statusDocumento)
    {
        $this->statusDocumento = $statusDocumento;

        return $this;
    }

    /**
     * Get status de fraude do documento (0- false, 1- true)
     *
     * @return  int
     */
    public function getIndicadorFraude()
    {
        return $this->indicadorFraude;
    }

    /**
     * Set status de fraude do documento (0- false, 1- true)
     *
     * @param  int  $indicadorFraude  status de fraude do documento (0- false, 1- true)
     *
     * @return  self
     */
    public function setIndicadorFraude(int $indicadorFraude)
    {
        $this->indicadorFraude = $indicadorFraude;

        return $this;
    }

    /**
     * Get id unica da execucao do tipo cnpj
     *
     * @return  String
     */
    public function getExecutionId()
    {
        return $this->executionId;
    }

    /**
     * Set id unica da execucao do tipo cnpj
     *
     * @param  String  $executionId  id unica da execucao do tipo cnpj
     *
     * @return  self
     */
    public function setExecutionId(String $executionId)
    {
        $this->executionId = $executionId;

        return $this;
    }

    /**
     * Get data da ultima atualizacao do documento
     *
     * @return  String
     */
    public function getDataAtualizacao()
    {
        return $this->dataAtualizacao;
    }

    /**
     * Set data da ultima atualizacao do documento
     *
     * @param  String  $dataAtualizacao  data da ultima atualizacao do documento
     *
     * @return  self
     */
    public function setDataAtualizacao(String $dataAtualizacao)
    {
        $this->dataAtualizacao = $dataAtualizacao;

        return $this;
    }
}
