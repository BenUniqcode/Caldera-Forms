import './index.scss';

import {createElement, render, unmountComponentAtNode} from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import MainDashboard from './MainDashboard/MainDashboard';


/**
 * Controls the right side of the CF admin
 *
 * @since 1.8.6
 */
domReady(function () {
    render(<MainDashboard/>, document.getElementById('caldera-forms-clippy'));
    jQuery('.cf-entry-viewer-link').on('click', function () {
        unmountComponentAtNode(document.getElementById('caldera-forms-clippy'));
    })
});


