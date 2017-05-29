<?php


/**
 * Класс модели постраничной навигации.
 */
class Pager
{
    private $totalPages;
    private $recordsPerPage;
    private $templatePageUrl;
    private $currentPage;

    /**
     * Через конструктор задаються параметры.
     *
     * @param int    $totalRecords    Общее число записей
     * @param int    $recordsPerPage  Количество записей на одной странице
     * @param string $templatePageUrl Шаблон ссылки
     * @param int    $page            Номер текущей страницы
     */
    public function __construct($totalRecords, $recordsPerPage, $templatePageUrl, $page)
    {
        $this->totalPages = ceil($totalRecords / $recordsPerPage);
        $this->recordsPerPage = $recordsPerPage;
        $this->templatePageUrl = $templatePageUrl;
        $this->currentPage = $page;
    }

    /**
     * Получить ссылку для указаной страницы
     * @param  int $page Номер страницы
     * @return string       Ссылка для страницы с указанным номером
     */
    public function getLinkForPage($page)
    {
        $queryArray = array();
        if ($page !== 1) {
            $queryArray['page'] = $page;
        }

        $url = $this->templatePageUrl.(!empty($queryArray) ? '?'.http_build_query($queryArray) : '');

        return $url;
    }

    /**
     * Сколько пропустить элементов при запросе к базе
     */
    public function getOffset()
    {
        $records = $this->getRecordsPerPage();

        return $records * $this->getCurrentPage() - $records;
    }

    /**
     * Get the value of Класс модели постраничной навигации.
     *
     * @return mixed
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Set the value of Класс модели постраничной навигации.
     *
     * @param mixed totalPages
     *
     * @return self
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    /**
     * Get the value of Records Per Page.
     *
     * @return mixed
     */
    public function getRecordsPerPage()
    {
        return $this->recordsPerPage;
    }

    /**
     * Set the value of Records Per Page.
     *
     * @param mixed recordsPerPage
     *
     * @return self
     */
    public function setRecordsPerPage($recordsPerPage)
    {
        $this->recordsPerPage = $recordsPerPage;

        return $this;
    }

    /**
     * Get the value of Template Page Url.
     *
     * @return mixed
     */
    public function getTemplatePageUrl()
    {
        return $this->templatePageUrl;
    }

    /**
     * Set the value of Template Page Url.
     *
     * @param mixed templatePageUrl
     *
     * @return self
     */
    public function setTemplatePageUrl($templatePageUrl)
    {
        $this->templatePageUrl = $templatePageUrl;

        return $this;
    }

    /**
     * Get the value of Current Page.
     *
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the value of Current Page.
     *
     * @param mixed currentPage
     *
     * @return self
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }
}
