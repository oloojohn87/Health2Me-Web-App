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
                text: "Add a Patient to Health2Me<br>",
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
                text: "Create a new folder in your computer and gather all your reports together.<br>",
                align: "left",
                font: ['varela-round, sans-serif', 18, "rgba(166,170,169,1.00)", "100", "none", "normal"]
            },
            {
                id: 'SoundA2-22',
                type: 'audio',
                tag: 'audio',
                rect: ['21', '268','320px','45px','auto', 'auto'],
                source: ['/TutorialBox/media/SoundA2-22.mp3']
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
                    rect: ['12px', '1px','40px','52px','auto', 'auto'],
                    text: "B",
                    align: "left",
                    font: ['varela-round, sans-serif', 42, "rgba(34,174,255,1.00)", "100", "none", "normal"]
                }]
            },
            {
                id: 'NewFolder',
                type: 'image',
                rect: ['500px', '50px','321px','249px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"NewFolder.png",'0px','0px']
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
            "${_Text5Copy3}": [
                ["style", "top", '1px'],
                ["style", "font-size", '42px'],
                ["style", "height", '52px'],
                ["color", "color", 'rgba(34,174,255,1)'],
                ["style", "left", '12px'],
                ["style", "width", '40px']
            ],
            "${_NewFolder}": [
                ["style", "top", '50px'],
                ["style", "height", '249px'],
                ["style", "opacity", '0'],
                ["style", "left", '500px'],
                ["style", "width", '321px']
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
            "${_background1}": [
                ["style", "left", '57px'],
                ["style", "top", '-72px']
            ],
            "${_LogoH2MDifuso}": [
                ["transform", "scaleX", '1'],
                ["style", "opacity", '1'],
                ["style", "left", '222px'],
                ["style", "top", '-20px']
            ],
            "${_Stage}": [
                ["color", "background-color", 'rgba(255,255,255,1)'],
                ["style", "height", '330px'],
                ["style", "width", '830px']
            ],
            "${_PlayButton}": [
                ["style", "top", '279px'],
                ["style", "left", '395px'],
                ["style", "height", '40px'],
                ["style", "opacity", '1'],
                ["style", "cursor", 'pointer'],
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
            "${_EllipseCopy3}": [
                ["color", "background-color", 'rgba(172,224,255,1)'],
                ["style", "left", '0px'],
                ["style", "top", '0px']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 7564,
            autoPlay: true,
            timeline: [
                { id: "eid82", tween: [ "style", "${_TextoB}", "opacity", '1', { fromValue: '0.000000'}], position: 0, duration: 2000 },
                { id: "eid54", tween: [ "style", "${_PlayButton}", "opacity", '0.33', { fromValue: '1'}], position: 0, duration: 402 },
                { id: "eid86", tween: [ "style", "${_TextoB}", "left", '90px', { fromValue: '-377px'}], position: 0, duration: 2000 },
                { id: "eid46", tween: [ "style", "${_LogoH2MDifuso}", "opacity", '0.08', { fromValue: '1'}], position: 0, duration: 2000 },
                { id: "eid92", tween: [ "style", "${_GrupoRotuloB}", "opacity", '0.489615', { fromValue: '0.02'}], position: 0, duration: 2000 },
                { id: "eid34", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 0, duration: 0 },
                { id: "eid35", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 2000, duration: 0 },
                { id: "eid98", tween: [ "style", "${_NewFolder}", "opacity", '0', { fromValue: '0'}], position: 0, duration: 0 },
                { id: "eid97", tween: [ "style", "${_NewFolder}", "opacity", '1', { fromValue: '0'}], position: 1500, duration: 2500, easing: "easeInCubic" },
                { id: "eid87", trigger: [ function executeMediaFunction(e, data) { this._executeMediaAction(e, data); }, ['play', '${_SoundA2-22}', [] ], ""], position: 2000 }            ]
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
})(jQuery, AdobeEdge, "EDGE-44");
