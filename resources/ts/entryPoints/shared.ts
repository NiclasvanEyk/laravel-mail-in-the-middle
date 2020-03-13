import * as ReactDom  from 'react-dom';
import jQuery from 'jquery'
(window as any).$ = jQuery;
(window as any).jQuery = jQuery;
import {ReactElement} from "react";
import 'bootstrap/dist/js/bootstrap.min'
import '../lib/var_dump';

export function render(component: ReactElement) {
    const appRoot = document.getElementById('mail-in-the-middle')

    if (appRoot !== null) {
        ReactDom.render(component, appRoot)
    } else {
        console.log('No render target was found!')
    }
}
