/*
* COLOR: #COLOR#
* COLOR_HOVER: #COLOR_HOVER#
*/
.theme-color {
    color: #COLOR#;
}
.theme-color:hover,
.theme-color-hover:hover,
.theme-color-hover-parent:hover .theme-color-hover {
    color: #COLOR_HOVER# !important;
}
.theme-bg,
.theme-bg-active.active,
.active .theme-bg-active,
.theme-before-bg::before,
.theme-after-bg::after {
    background-color: #COLOR#;
}
a.theme-bg:hover,
a:hover .theme-bg,
.theme-before-bg::before:hover,
.theme-after-bg::after:hover {
    background-color: #COLOR_HOVER#;
}
.theme-bg-hover:hover,
.theme-bg-hover-parent:hover .theme-bg-hover {
    background-color: #COLOR_HOVER#;
}
.theme-border,
.theme-border-class-active.active,
.theme-border-class-active .active,
.theme-border-hover:hover {
    border-color: #COLOR#;
}
.theme-border-hover:hover,
.theme-border-hover-parent:hover .theme-border-hover {
    border-color: #COLOR_HOVER#;
}
.theme-fill {
    fill: #COLOR#;
}
.theme-fill-hover:hover,
.theme-fill-hover:hover svg {
    fill: #COLOR_HOVER#;
}
.theme-stroke {
    stroke: #COLOR#;
}
.theme-stroke-hover:hover,
.theme-stroke-hover:hover svg {
    stroke: #COLOR_HOVER# !important;
}

.btn-primary,
.btn-primary:disabled {
    background-color: #COLOR#;
    border-color: #COLOR#;
}
.btn-primary:disabled { opacity: .5; }

.btn-primary:hover, .btn-transparent:hover,
.btn-primary:active, .btn-transparent:active,
.btn-primary:not(:disabled):not(:disabled):active, .btn-transparent:not(:disabled):not(:disabled):active,
.btn-primary:focus, .btn-transparent:focus,
.custom-file-input:focus ~ .custom-file-label {
    background-color: #COLOR_HOVER#;
    border-color: #COLOR_HOVER#;
}
.btn-transparent {
    border-color: #COLOR_035#;
    color: #COLOR#;
}
.btn-transparent:hover,
.btn-transparent:active,
.btn-transparent:focus,
.custom-file-input:focus ~ .custom-file-label {
    color: #ffffff;
    border-color: #COLOR# !important;
}
.btn-white {
    color: #COLOR#;
}
.btn-white:hover {
    background-color: #COLOR_HOVER#;
    border-color: #COLOR_HOVER#;
}
a {
    color: #COLOR#;
}
a:hover:not(.btn):not(.theme-exclude-hover) {
    color: #COLOR_HOVER# !important;
    text-decoration: none;
}
a.active:not(.btn):not(.theme-exclude-hover) {
    color: #COLOR_HOVER#;
    text-decoration: none;
}

/* custom checkbox */
.custom-checkbox input:checked ~ label::before,
.custom-checkbox input:hover ~ label::before,
.custom-checkbox label:hover::before,
.custom-checkbox:not(:disabled):active ~ label::before {
    border-color: #COLOR# !important;
    background-color: #COLOR# !important;
}
.custom-checkbox input:focus:not(:checked) ~ label::before {
    border-color: #COLOR# !important;
}

/* custom radio */
.custom-control-input:checked ~ .custom-control-label::before {
    border-color: #COLOR# !important;
    background-color: #COLOR# !important;
}

/* lists */
.theme-ul ul li::before {
    color: #COLOR#;
}
