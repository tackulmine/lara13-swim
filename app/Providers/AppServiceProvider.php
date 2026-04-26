<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->runningInConsole()) {
            Blade::if('canrole', function ($expression) {
                // logger()->info('expression');
                // logger()->info($expression);
                // logger()->info('check role');
                // logger()->info((bool) auth()->user()->checkRole($expression));
                // logger()->info('== ++ ==');
                return auth()->user()->checkRole($expression);
            });
            // form custom generator
            // Form::component('bs4HorPlain', 'components.bs4.form.horizontal.plain', [
            //     'name',
            //     'value' => null,
            //     'attributes' => [],
            //     'label' => null,
            //     'formGroupClasses' => null,
            //     'formLabelClasses' => null,
            // ]);
            // Form::component('bs4HorText', 'components.bs4.form.horizontal.text', [
            //     'name',
            //     'value' => null,
            //     'attributes' => [],
            //     'label' => null,
            //     'formGroupClasses' => null,
            //     'formLabelClasses' => null,
            //     'formHelpText' => null,
            // ]);
            // Form::component('bs4HorNumber', 'components.bs4.form.horizontal.number', [
            //     'name',
            //     'value' => null,
            //     'attributes' => [],
            //     'label' => null,
            // ]);
            // Form::component('bs4HorTextarea', 'components.bs4.form.horizontal.textarea', [
            //     'name',
            //     'value' => null,
            //     'attributes' => [],
            //     'label' => null,
            // ]);
            // Form::component('bs4HorHidden', 'components.bs4.form.horizontal.hidden', [
            //     'name',
            //     'value' => null,
            //     'attributes' => [],
            //     'label' => null,
            // ]);
            // Form::component('bs4HorEmail', 'components.bs4.form.horizontal.email', [
            //     'name',
            //     'value' => null,
            //     'attributes' => [],
            //     'label' => null,
            // ]);
            // Form::component('bs4HorDate', 'components.bs4.form.horizontal.date', [
            //     'name',
            //     'value' => null,
            //     'attributes' => [],
            //     'label' => null,
            // ]);
            // Form::component('bs4HorPassword', 'components.bs4.form.horizontal.password', [
            //     'name',
            //     'attributes' => [],
            //     'label' => null,
            //     'helpText' => 'default',
            // ]);
            // Form::component('bs4HorFile', 'components.bs4.form.horizontal.file', [
            //     'name',
            //     'attributes' => [],
            //     'label' => null,
            //     'fileLabel' => null,
            //     'oldPreviewFileHtml' => null,
            // ]);
            // Form::component('bs4HorSelect', 'components.bs4.form.horizontal.select', [
            //     'name',
            //     'options' => [],
            //     'value' => null,
            //     'attributes' => [],
            //     'label' => null,
            // ]);
            // Form::component('bs4HorCheckboxes', 'components.bs4.form.horizontal.checkboxes', [
            //     'name',
            //     'checkboxes' => [],
            //     'values' => [],
            //     'label' => null,
            //     'inputAttributes' => [],
            //     'separator' => 'inline',
            //     'formGroupClasses' => null,
            //     'formLabelClasses' => null,
            //     'formItemClasses' => null,
            // ]);
            // Form::component('bs4HorCheckbox', 'components.bs4.form.horizontal.checkbox', [
            //     'name',
            //     'value' => null,
            //     'checked' => false,
            //     'display' => '',
            //     'inputAttributes' => [],
            //     'divClasses' => '',
            // ]);
            // Form::component('bs4HorCheckboxSwitch', 'components.bs4.form.horizontal.checkbox-switch', [
            //     'name',
            //     'value' => null,
            //     'checked' => false,
            //     'display' => '',
            //     'inputAttributes' => [],
            //     'divClasses' => '',
            // ]);
            // Form::component('bs4HorRadios', 'components.bs4.form.horizontal.radios', [
            //     'name',
            //     'radios' => [],
            //     'value' => null,
            //     'label' => null,
            //     'inputAttributes' => [],
            //     'separator' => 'inline',
            //     'formGroupClasses' => null,
            //     'formLabelClasses' => null,
            //     'formItemClasses' => null,
            // ]);
            // Form::component('bs4HorRadio', 'components.bs4.form.horizontal.radio', [
            //     'name',
            //     'value' => null,
            //     'checked' => false,
            //     'display' => '',
            //     'inputAttributes' => [],
            //     'divClasses' => '',
            // ]);
            Carbon::setLocale('id');
        }
    }
}
