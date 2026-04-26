<style type="text/css">
  {{ file_get_contents(public_path('assets/back/css/bootstrap-v4.0.0.min.css')) }} @font-face {
    font-family: 'Bahnschrift';
    src: url('{{ asset('assets') }}/fonts/Bahnschrift.eot');
    src: url('{{ asset('assets') }}/fonts/Bahnschrift.eot?#iefix') format('embedded-opentype'),
      url('{{ asset('assets') }}/fonts/Bahnschrift.woff2') format('woff2'),
      url('{{ asset('assets') }}/fonts/Bahnschrift.woff') format('woff'),
      url('{{ asset('assets') }}/fonts/Bahnschrift.ttf') format('truetype'),
      url('{{ asset('assets') }}/fonts/Bahnschrift.svg#Bahnschrift') format('svg');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
  }

  @page {
    /* Set page margins to none to allow the background to cover the whole page */
    margin: 100px 0 30px 0;
  }

  header,
  footer {
    position: fixed;
    left: 1cm;
    right: 1cm;
    text-align: center;
    color: #000;
    font-size: 0.9em;
  }

  /** Define the header rules **/
  header {
    top: -60px;
    border-bottom: 0.1pt solid #aaa;
  }

  /** Define the footer rules (optional) **/
  footer {
    height: 50px;
    line-height: 35px;
    bottom: 0;
    border-top: 0.1pt solid #aaa;
  }

  body {
    /* Define body styles */
    /* font-family: Helvetica, Arial, sans-serif; */
    font-family: Bahnschrift;
    font-size: .8rem;
    position: relative;
    /* Required for z-index context */
    /* margin: 0; */
    padding: 0;
    /** Ensure content doesn't overlap with header/footer **/
    margin-top: 100px;
    margin-bottom: 50px;
  }

  .table td,
  .table th {
    border-color: #000000;
    vertical-align: baseline;
  }

  .table th {
    font-weight: 500;
  }

  .table-sm td,
  .table-sm th {
    padding-top: .1rem;
    padding-bottom: .1rem;
  }

  .table-borderless td,
  .table-borderless th {
    border-top: none;
    border-bottom: none;
  }

  .table-borderless th {
    border-bottom: 1px solid #000000;
  }

  #watermark {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* Center the background image */
    background-image: url({!! asset('assets/front/images/logo/logo-centrum.jpg') !!});
    -moz-filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
    -o-filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
    -webkit-filter: grayscale(100%);
    filter: gray;
    filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
    opacity: 0.15;
    background-repeat: no-repeat;
    background-position: center center;
    /* Optional: Adjust background size (dompdf has limited support for 'cover'/'contain', use fixed sizes if needed) */
    /* background-size: contain; */
    z-index: -1000;
    /* Ensure it stays behind all content */
  }

  /* Style for your actual content */
  .content {
    padding: 0 1cm;
    /* Add padding so content doesn't overlap the edges or background image */
    position: relative;
    z-index: 0;
  }

  .page-break {
    page-break-after: always;
  }

  /* .pagenum:before {
    content: "Page " counter(page) " of " counter(pages);
  } */

  .text-underline {
    text-decoration: underline;
  }
</style>
