// Create a template for the download button
$.fancybox.defaults.btnTpl.download =
    '<a download data-fancybox-download class="fancybox-button fancybox-button--download" title="Download" href="javascript:;">' +
    '<svg xmlns="www.w3.org" viewBox="0 0 24 24"><path d="M18 15v3H6v-3H4v3c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-3h-2zm-1-4l-1.41-1.41L13 12.17V4h-2v8.17L8.41 9.59 7 11l5 5 5-5z"/></svg>' +
    '</a>';

// Choose which buttons to display by default, including the new 'download' button
$.fancybox.defaults.buttons = [
    'slideShow',
    'fullScreen',
    'thumbs',
    'download', // Add the download button
    'close'
];

// Initialize Fancybox and dynamically update the download link's href and filename
$('[data-fancybox]').fancybox({
    beforeShow: function (instance, current) {
        // Find the download button within the container
        var $downloadButton = instance.$refs.container.find('[data-fancybox-download]');
        // Set the href attribute to the current item's source URL
        $downloadButton.attr('href', current.src);

        // Optionally, set the download filename using the data attribute from the HTML element
        var fileName = $(current.opts.$orig).data('download-filename');
        if (fileName) {
            $downloadButton.attr('download', fileName);
        }
    }
});