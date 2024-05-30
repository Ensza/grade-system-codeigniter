<div id="loader"
class="absolute w-full h-full bg-white bg-opacity-50 top-0 left-0 flex items-center" 
style="display: none;">
    <style>
        /* HTML: <div class="loader"></div> */
        .loader {
        width: 50px;
        padding: 8px;
        aspect-ratio: 1;
        border-radius: 50%;
        background: #25b09b;
        --_m: 
            conic-gradient(#0000 10%,#000),
            linear-gradient(#000 0 0) content-box;
        -webkit-mask: var(--_m);
                mask: var(--_m);
        -webkit-mask-composite: source-out;
                mask-composite: subtract;
        animation: l3 1s infinite linear;
        }
        @keyframes l3 {to{transform: rotate(1turn)}}
    </style>
    <div class="loader mx-auto"></div>
</div>