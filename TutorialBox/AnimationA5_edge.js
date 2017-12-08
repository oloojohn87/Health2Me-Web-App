/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='/TutorialBox/images/';

var fonts = {};    fonts['varela-round, sans-serif']='<script src=\"http://use.edgefonts.net/varela-round:n4:all.js\"></script>';

var opts = {};
var resources = [
];
var symbols = {
"stage": {
    version: "3.0.0",
    minimumCompatibleVersion: "3.0.0",
    build: "3.0.0.322",
    baseState: "Base State",
    scaleToFit: "none",
    centerStage: "none",
    initialState: "Base State",
    gpuAccelerate: true,
    resizeInstances: false,
    content: {
            dom: [
            {
                id: 'background1',
                type: 'image',
                rect: ['57px', '-72px','885px','666px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"background1.png",'0px','0px']
            },
            {
                id: 'LogoH2MDifuso',
                type: 'image',
                rect: ['222px', '-20px','860px','350px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"LogoH2MDifuso.png",'0px','0px']
            },
            {
                id: 'Text',
                type: 'text',
                rect: ['84px', '84px','257px','33px','auto', 'auto'],
                text: "Add a Patient to Health2Me",
                font: ['varela-round, sans-serif', 24, "rgba(0,0,0,1)", "100", "none", "normal"]
            },
            {
                id: 'PlayButton',
                type: 'image',
                rect: ['395px', '279px','40px','40px','auto', 'auto'],
                cursor: ['pointer'],
                fill: ["rgba(0,0,0,0)",im+"PlayButton.png",'0px','0px']
            },
            {
                id: 'TextoB',
                type: 'text',
                rect: ['36px', '104px','367px','79px','auto', 'auto'],
                text: "Drag and Drop the files into the folder types you choose.<br>",
                align: "left",
                font: ['varela-round, sans-serif', 18, "rgba(166,170,169,1.00)", "100", "none", "normal"]
            },
            {
                id: 'GrupoRotuloB',
                type: 'group',
                rect: ['21px', '95px','52','53','auto', 'auto'],
                opacity: 0.48961509146341,
                c: [
                {
                    id: 'GroupCopy3',
                    type: 'group',
                    rect: ['0px', '0px','52','53','auto', 'auto'],
                    c: [
                    {
                        id: 'EllipseCopy3',
                        type: 'ellipse',
                        rect: ['0px', '0px','52px','53px','auto', 'auto'],
                        borderRadius: ["50%", "50%", "50%", "50%"],
                        fill: ["rgba(172,224,255,1.00)"],
                        stroke: [0,"rgb(0, 0, 0)","none"]
                    }]
                },
                {
                    id: 'Text5Copy3',
                    type: 'text',
                    rect: ['10px', '2px','40px','52px','auto', 'auto'],
                    text: "D",
                    align: "left",
                    font: ['varela-round, sans-serif', 42, "rgba(34,174,255,1.00)", "100", "none", "normal"]
                }]
            },
            {
                id: 'SoundA2-4',
                type: 'audio',
                tag: 'audio',
                rect: ['-9', '291','320px','45px','auto', 'auto'],
                source: ['/TutorialBox/media/SoundA2-4.mp3']
            },
            {
                id: 'DragDrop',
                type: 'image',
                rect: ['auto', '27px','385px','243px','16px', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"DragDrop.png",'0px','0px']
            },
            {
                id: 'Report5',
                type: 'image',
                rect: ['508px', '68px','21px','28px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"Report5.png",'0px','0px']
            },
            {
                id: 'Report1',
                type: 'image',
                rect: ['569px', '68px','24px','28px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"Report1.png",'0px','0px']
            }],
            symbolInstances: [

            ]
        },
    states: {
        "Base State": {
            "${_GrupoRotuloB}": [
                ["style", "top", '95px'],
                ["style", "opacity", '0.02'],
                ["style", "left", '21px']
            ],
            "${_GroupCopy3}": [
                ["style", "top", '0px'],
                ["style", "left", '0px']
            ],
            "${_PlayButton}": [
                ["style", "top", '279px'],
                ["style", "left", '395px'],
                ["style", "height", '40px'],
                ["style", "opacity", '1'],
                ["style", "cursor", 'pointer'],
                ["style", "width", '40px']
            ],
            "${_Text5Copy3}": [
                ["style", "top", '2px'],
                ["style", "font-size", '42px'],
                ["style", "height", '52px'],
                ["color", "color", 'rgba(34,174,255,1)'],
                ["style", "left", '10px'],
                ["style", "width", '40px']
            ],
            "${_TextoB}": [
                ["style", "top", '96px'],
                ["style", "font-size", '18px'],
                ["color", "color", 'rgba(166,170,169,1)'],
                ["style", "height", '79px'],
                ["style", "opacity", '0'],
                ["style", "left", '-377px'],
                ["style", "width", '367px']
            ],
            "${_background1}": [
                ["style", "left", '57px'],
                ["style", "top", '-72px']
            ],
            "${_Text}": [
                ["color", "color", 'rgba(34,174,255,1.00)'],
                ["style", "font-weight", '100'],
                ["style", "left", '36px'],
                ["style", "width", '554px'],
                ["style", "top", '40px'],
                ["style", "font-style", 'normal'],
                ["style", "font-family", 'varela-round, sans-serif'],
                ["style", "text-decoration", 'none'],
                ["style", "font-size", '26px']
            ],
            "${_DragDrop}": [
                ["style", "top", '27px'],
                ["style", "right", '16px'],
                ["style", "height", '243px'],
                ["style", "opacity", '0'],
                ["style", "left", 'auto'],
                ["style", "width", '385px']
            ],
            "${_Stage}": [
                ["color", "background-color", 'rgba(255,255,255,1)'],
                ["style", "height", '330px'],
                ["style", "width", '830px']
            ],
            "${_LogoH2MDifuso}": [
                ["transform", "scaleX", '1'],
                ["style", "opacity", '1'],
                ["style", "left", '222px'],
                ["style", "top", '-20px']
            ],
            "${_EllipseCopy3}": [
                ["color", "background-color", 'rgba(172,224,255,1)'],
                ["style", "left", '0px'],
                ["style", "top", '0px']
            ],
            "${_Report5}": [
                ["style", "top", '81px'],
                ["style", "opacity", '0'],
                ["style", "left", '514px']
            ],
            "${_Report1}": [
                ["style", "top", '81px'],
                ["style", "opacity", '0'],
                ["style", "left", '542px']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 6467,
            autoPlay: true,
            timeline: [
                { id: "eid154", tween: [ "style", "${_Report5}", "top", '135px', { fromValue: '81px'}], position: 4000, duration: 1500 },
                { id: "eid138", tween: [ "style", "${_Report1}", "opacity", '1', { fromValue: '0'}], position: 2500, duration: 500 },
                { id: "eid143", tween: [ "style", "${_Report1}", "opacity", '0.15', { fromValue: '1'}], position: 3000, duration: 1500 },
                { id: "eid86", tween: [ "style", "${_TextoB}", "left", '90px', { fromValue: '-377px'}], position: 0, duration: 2000 },
                { id: "eid92", tween: [ "style", "${_GrupoRotuloB}", "opacity", '0.489615', { fromValue: '0.02'}], position: 0, duration: 2000 },
                { id: "eid34", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 0, duration: 0 },
                { id: "eid35", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 2000, duration: 0 },
                { id: "eid141", tween: [ "style", "${_Report1}", "left", '603px', { fromValue: '542px'}], position: 2500, duration: 1500 },
                { id: "eid82", tween: [ "style", "${_TextoB}", "opacity", '1', { fromValue: '0.000000'}], position: 0, duration: 2000 },
                { id: "eid54", tween: [ "style", "${_PlayButton}", "opacity", '0.33', { fromValue: '1'}], position: 0, duration: 402 },
                { id: "eid146", tween: [ "style", "${_Report1}", "top", '186px', { fromValue: '81px'}], position: 2500, duration: 1500 },
                { id: "eid160", tween: [ "style", "${_Report5}", "opacity", '0', { fromValue: '0'}], position: 0, duration: 0 },
                { id: "eid159", tween: [ "style", "${_Report5}", "opacity", '1', { fromValue: '0'}], position: 3500, duration: 500 },
                { id: "eid156", tween: [ "style", "${_Report5}", "opacity", '0', { fromValue: '1'}], position: 5500, duration: 500 },
                { id: "eid46", tween: [ "style", "${_LogoH2MDifuso}", "opacity", '0.08', { fromValue: '1'}], position: 0, duration: 2000 },
                { id: "eid130", tween: [ "style", "${_DragDrop}", "opacity", '0', { fromValue: '0'}], position: 0, duration: 0, easing: "easeInCubic" },
                { id: "eid133", tween: [ "style", "${_DragDrop}", "opacity", '1', { fromValue: '0.000000'}], position: 1500, duration: 1500, easing: "easeInCubic" },
                { id: "eid153", tween: [ "style", "${_Report5}", "left", '701px', { fromValue: '514px'}], position: 4000, duration: 1500 },
                { id: "eid161", trigger: [ function executeMediaFunction(e, data) { this._executeMediaAction(e, data); }, ['play', '${_SoundA2-4}', [] ], ""], position: 2000 }            ]
        }
    }
}
};


Edge.registerCompositionDefn(compId, symbols, fonts, resources, opts);

/**
 * Adobe Edge DOM Ready Event Handler
 */
$(window).ready(function() {
     Edge.launchComposition(compId);
});
})(jQuery, AdobeEdge, "EDGE-46");
