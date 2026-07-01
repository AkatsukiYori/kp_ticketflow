
import ApexCharts from 'apexcharts';
import Alpine from 'alpinejs';

import './bootstrap';
import * as bootstrap from 'bootstrap';
import $ from 'jquery';

import DataTable from 'datatables.net-dt';

import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
);

window.Alpine = Alpine;
window.FilePond = FilePond;
window.ApexCharts = ApexCharts;

window.$ = $;
window.jQuery = $;

window.bootstrap = bootstrap;
window.DataTable = DataTable;
Alpine.start();