<?php

namespace App\Traits;

use Livewire\Attributes\Url;

trait Pagination
{
    #[Url(as: 'pag', except: 1)]
    public int $page = 1;

    public int $max_num_pages = 1;

    public function previousPage(): void
    {
        if($this->page > 1) {
            $this->page--;
        }

        $this->handlePageUpdate();
    }

    public function gotoPage(int $page): void
    {
        if($page >= 1 && $page <= $this->max_num_pages) {
            $this->page = $page;
        }

        $this->handlePageUpdate();
    }

    public function nextPage(): void
    {
        if($this->page < $this->max_num_pages) {
            $this->page++;
        }

        $this->handlePageUpdate();
    }

    // This method should be implemented by the component using the trait
    protected function handlePageUpdate(): void
    {
        // This will be overridden by the component
    }
}
