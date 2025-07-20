<?php

namespace app\display\nodes\Paginator;

/**
 * Class Brito101 Paginator
 *
 * @author Rodrigo Brito <https://github.com/brito101>
 * @package Brito101\Paginator
 */
class Paginator
{
    /** @var int */
    private $page;

    /** @var int */
    private $pages;

    /** @var int */
    private $rows;

    /** @var int */
    private $limit;

    /** @var int */
    private $offset;

    /** @var int */
    private $range;

    /** @var string */
    private $link;

    /** @var string */
    private $title;

    /** @var string */
    private $class;

    /** @var string */
    private $hash;

    /** @var array */
    private $first;

    /** @var array */
    private $last;

    /** @var array */
    private $prev;

    /** @var array */
    private $next;

    /** @var string */
    private $params;

    /**
     * Paginator constructor.
     * @param string|null $link
     * @param string|null $title
     * @param array|null $first
     * @param array|null $last
     */
    public function __construct(string $link = null, string $title = null, array $first = null, array $last = null, array $prev = null, array $next = null)
    {
        $cleared_url = preg_replace('/([&]{0,1}'.($link ?? "page").'=[0-9]+)/i', '', $_SERVER["QUERY_STRING"]);
        if(mb_strlen($cleared_url)<2) {
            $this->link = '?'.($link ?? "page").'=';
        } else {
            $this->link = '?'.$cleared_url.'&'.($link ?? "page").'=';
        }
        $this->title = ($title ?? "Page");
        $this->first = ($first ?? ["First page", "I&lt;"]);
        $this->last = ($last ?? ["Last page", "&gt;I"]);
        $this->prev = ($prev ?? ["Previous page", "&lt;"]);
        $this->next = ($next ?? ["Next page", "&gt;"]);

        return $this;
    }

    /**
     * @param int $rows
     * @param int $limit
     * @param int|null $page
     * @param int $range
     * @param string|null $hash
     * @param array $params
     */
    public function pager(int $rows, int $limit = 10, int $page = null, int $range = 2, string $hash = null, array $params = []): Paginator
    {
        $this->rows = $this->toPositive($rows);
        $this->limit = $this->toPositive($limit);
        $this->range = $this->toPositive($range);
        $this->pages = (int)ceil($this->rows / $this->limit);
        $this->page = ($page <= $this->pages ? $this->toPositive($page) : $this->pages);

        $this->offset = (($this->page * $this->limit) - $this->limit >= 0 ? ($this->page * $this->limit) - $this->limit : 0);
        $this->hash = (!empty($hash) ? "#{$hash}" : null);

        $this->addGetParams($params);

        if ($this->rows && $this->offset >= $this->rows) {
            header("Location: {$this->link}" . ceil($this->rows / $this->limit));
            exit;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function page()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function pages()
    {
        return $this->pages;
    }

    /**
     * @param string $cssClass
     * @param bool $fixedFirstAndLastPage
     * @return null|string
     */
    public function render(string $cssClass = null, bool $fixedFirstAndLastPage = true, bool $showLeftRightArrows = false): ?string
    {
        $this->class = $cssClass ?? "paginator";

        if ($this->rows > $this->limit) :
            $paginator = "<nav class=\"{$this->class}\">";
            $paginator .= $this->firstPage($fixedFirstAndLastPage);
            if($showLeftRightArrows) $paginator .= $this->previousPage();
            $paginator .= $this->beforePages();
            $paginator .= "<span class=\"{$this->class}_item {$this->class}_active\">{$this->page}</span>";
            $paginator .= $this->afterPages();
            if($showLeftRightArrows) $paginator .= $this->nextPage();
            $paginator .= $this->lastPage($fixedFirstAndLastPage);
            $paginator .= "</nav>";
            echo $paginator;
        endif;

        return null;
    }

    /**
     * @return null|string
     */
    private function beforePages(): ?string
    {
        $before = null;
        for ($iPag = $this->page - $this->range; $iPag <= $this->page - 1; $iPag++) :
            if ($iPag >= 1) :
                $before .= "<a class='{$this->class}_item' title=\"{$this->title} {$iPag}\" href=\"{$this->link}{$iPag}{$this->hash}{$this->params}\">{$iPag}</a>";
            endif;
        endfor;

        return $before;
    }

    /**
     * @return string|null
     */
    private function afterPages(): ?string
    {
        $after = null;
        for ($dPag = $this->page + 1; $dPag <= $this->page + $this->range; $dPag++) :
            if ($dPag <= $this->pages) :
                $after .= "<a class='{$this->class}_item' title=\"{$this->title} {$dPag}\" href=\"{$this->link}{$dPag}{$this->hash}{$this->params}\">{$dPag}</a>";
            endif;
        endfor;

        return $after;
    }

   /**
    * @param bool $fixedFirstAndLastPage
    * @return string|null
    */
    public function previousPage(): ?string
    {
        if ($this->page != 1) {
            $dPag = $this->page-1;
            return "<a class='{$this->class}_item' title=\"{$this->prev[0]}\" href=\"{$this->link}{$dPag}{$this->hash}{$this->params}\">{$this->prev[1]}</a>";
        } else return null;
    }

    /**
     * @param bool $fixedFirstAndLastPage
     * @return string|null
     */
    public function firstPage(bool $fixedFirstAndLastPage = true): ?string
    {
        if ($this->page != 1) {
            if($fixedFirstAndLastPage){
                return "<a class='{$this->class}_item' title=\"{$this->first[0]}\" href=\"{$this->link}1{$this->hash}{$this->params}\">{$this->first[1]}</a>";
            }
        } else if ($fixedFirstAndLastPage) {
            return "<span class='{$this->class}_item'>{$this->first[1]}</span>";
        }
        return null;
    }

   /**
    * @param bool $fixedFirstAndLastPage
    * @return string|null
    */
    public function nextPage(): ?string
    {
        if ($this->page != $this->pages) {
            $dPag = $this->page + 1;
            return "<a class='{$this->class}_item' title=\"{$this->next[0]}\" href=\"{$this->link}{$dPag}{$this->hash}{$this->params}\">{$this->next[1]}</a>";
        } else return null;
    }

    /**
     * @param bool $fixedFirstAndLastPage
     * @return string|null
     */
    public function lastPage(bool $fixedFirstAndLastPage = true): ?string
    {
        if ($this->page != $this->pages) {
            if($fixedFirstAndLastPage){
                return "<a class='{$this->class}_item' title=\"{$this->last[0]}\" href=\"{$this->link}{$this->pages}{$this->hash}{$this->params}\">{$this->last[1]}</a>";
            }
        } else if ($fixedFirstAndLastPage) {
            return "<span class='{$this->class}_item'>{$this->last[1]}</span>";
        }
        return null;
    }

    /**
     * @param $number
     * @return int
     */
    private function toPositive($number): int
    {
        return ($number >= 1 ? $number : 1);
    }

    /**
     * Add get parameters
     * @param array $params
     * @return null
     */
    private function addGetParams(array $params)
    {
        $this->params = '';

        if (count($params) > 0) {

            if (isset($params['page'])) {
                unset($params['page']);
            }

            $this->params  = '&';
            $this->params .= http_build_query($params);
        }

        return $this;
    }
}
