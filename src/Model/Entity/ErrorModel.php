<?php

namespace App\Model\Entity;

final class ErrorModel
{
    /**
     * @var int
     * id
     */
    private $id;

    /**
     * @var String
     * execution_id da consulta
     */
    private $executionId;

    /**
     * @var String
     * report_id do tipo de consulta realizada
     */
    private $reportId;

    /**
     * @var Array
     * mensagem de erro
     */
    private $errorList;

    /**
     * @var int
     * flag para indicar se erro nao existe mais no bd
     */
    private $deleted;


    public function __construct($id, $executionId, $reportId, $errorList, $deleted)
    {
        $this->id = $id;
        $this->executionId = $executionId;
        $this->reportId = $reportId;
        $this->errorList = $errorList;
        $this->deleted = $deleted;
    }


    /**
     * Get flag para indicar se erro nao existe mais no bd
     *
     * @return  int
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set flag para indicar se erro nao existe mais no bd
     *
     * @param  int  $deleted  flag para indicar se erro nao existe mais no bd
     *
     * @return  self
     */
    public function setDeleted(int $deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get lista de erros
     *
     * @return  Array
     */
    public function getErrorList()
    {
        return $this->errorList;
    }

    /**
     * Set lista de erros
     *
     * @param  Array  $errorList de mensagens de erro
     *
     * @return  self
     */
    public function setErrorList(String $errorList)
    {
        $this->errorList = $errorList;

        return $this;
    }

    /**
     * Get report_id do tipo de consulta realizada
     *
     * @return  String
     */
    public function getReportId()
    {
        return $this->reportId;
    }

    /**
     * Set report_id do tipo de consulta realizada
     *
     * @param  String  $reportId  report_id do tipo de consulta realizada
     *
     * @return  self
     */
    public function setReportId(String $reportId)
    {
        $this->reportId = $reportId;

        return $this;
    }

    /**
     * Get execution_id da consulta
     *
     * @return  String
     */
    public function getExecutionId()
    {
        return $this->executionId;
    }

    /**
     * Set execution_id da consulta
     *
     * @param  String  $executionId  execution_id da consulta
     *
     * @return  self
     */
    public function setExecutionId(String $executionId)
    {
        $this->executionId = $executionId;

        return $this;
    }
}
