import * as React from 'react'
import {HasChildren} from "../types/react"

// <ul className="list-group" style="height: 100vh; overflow-y: scroll">
//             <a
//                 className="list-group-item list-group-item-action"
//                 href="{{\VanEyk\MITM\Support\Route::resolve('overview')}}"
//             >
//                 <h2 className="my-3 font-weight-bold text-uppercase text-monospace">
//                     Mail in the Middle
//                 </h2>
//             </a>
//             @foreach($mails as $mail)
//
//             @endforeach
//
//             <div className="mt-3 w-100 d-flex flex-row justify-content-center">
//                 {{$mails->links()}}
//             </div>
//         </ul>
//     </div>
//     <div className="col-md-8">
//         <div className="container">
//             @component(\VanEyk\MITM\Support\View::component('mail-detail'), [
//             'mail' => $mails[$index - 1],
//             ])@endcomponent
//         </div>
//     </div>
// </div>

export function Container({children}: HasChildren) {
    return <div className="d-flex flexrow">
        { children }
    </div>
}

export function Master({children}: HasChildren) {
    return <div className="d-flex flex-column w-25 h-100 position-fixed border-right border-gray">
        {children}
    </div>
}

export function Detail({children}: HasChildren) {
    return <div className="flex-grow-1" style={{paddingLeft: '25%'}}>
        <div className="container">
            {children}
        </div>
    </div>
}
