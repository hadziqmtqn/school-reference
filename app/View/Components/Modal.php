<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public string $modalId;
    public ?string $title;
    public ?string $formId;
    public ?string $method;
    public ?string $url;
    public ?string $buttonId;

    /**
     * @param string $modalId
     * @param string|null $title
     * @param string|null $formId
     * @param string|null $method
     * @param string|null $url
     * @param string|null $buttonId
     */
    public function __construct(string $modalId, ?string $title, ?string $formId, ?string $method, ?string $url, ?string $buttonId)
    {
        $this->modalId = $modalId;
        $this->title = $title;
        $this->formId = $formId;
        $this->method = $method;
        $this->url = $url;
        $this->buttonId = $buttonId;
    }

    public function render(): View
    {
        return view('components.modal');
    }
}
