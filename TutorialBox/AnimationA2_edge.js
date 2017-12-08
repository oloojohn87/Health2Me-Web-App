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
                id: 'Text2',
                type: 'text',
                rect: ['36px', '104px','367px','79px','auto', 'auto'],
                text: "From your EMR, print your reports directly to PDF format. Alternatively, print them and scan them into PDF files.<br>",
                align: "left",
                font: ['varela-round, sans-serif', 18, "rgba(166,170,169,1.00)", "100", "none", "normal"]
            },
            {
                id: 'Computer',
                type: 'image',
                rect: ['560px', '-11px','179px','181px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"Computer.png",'0px','0px']
            },
            {
                id: 'PDFFILE',
                type: 'image',
                rect: ['623px', '57px','85px','91px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"PDFFILE.png",'0px','0px']
            },
            {
                id: 'ArrowDn',
                type: 'image',
                rect: ['650px', '175px','42px','52px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"ArrowDn.png",'0px','0px']
            },
            {
                id: 'PlayButton',
                type: 'image',
                rect: ['395px', '279px','40px','40px','auto', 'auto'],
                cursor: ['pointer'],
                fill: ["rgba(0,0,0,0)",im+"PlayButton.png",'0px','0px']
            },
            {
                id: 'Group2',
                type: 'group',
                rect: ['21px', '95px','52','53','auto', 'auto'],
                opacity: 0.48961509146341,
                c: [
                {
                    id: 'Group',
                    type: 'group',
                    rect: ['0px', '0px','52','53','auto', 'auto'],
                    c: [
                    {
                        id: 'Ellipse',
                        type: 'ellipse',
                        rect: ['0px', '0px','52px','53px','auto', 'auto'],
                        borderRadius: ["50%", "50%", "50%", "50%"],
                        fill: ["rgba(172,224,255,1.00)"],
                        stroke: [0,"rgb(0, 0, 0)","none"]
                    }]
                },
                {
                    id: 'Text5',
                    type: 'text',
                    rect: ['12px', '1px','40px','52px','auto', 'auto'],
                    text: "A",
                    align: "left",
                    font: ['varela-round, sans-serif', 42, "rgba(34,174,255,1.00)", "100", "none", "normal"]
                }]
            },
            {
                id: 'SoundA2-12',
                type: 'audio',
                tag: 'audio',
                rect: ['47', '313','320px','45px','auto', 'auto'],
                source: ['/TutorialBox/media/SoundA2-12.mp3']
            }],
            symbolInstances: [

            ]
        },
    states: {
        "Base State": {
            "${_Ellipse}": [
                ["color", "background-color", 'rgba(172,224,255,1.00)'],
                ["style", "left", '0px'],
                ["style", "top", '0px']
            ],
            "${_Computer}": [
                ["style", "top", '-11px'],
                ["style", "height", '181px'],
                ["style", "opacity", '0'],
                ["style", "left", '560px'],
                ["style", "width", '179px']
            ],
            "${_Text5Copy2}": [
                ["style", "top", '1px'],
                ["style", "font-size", '42px'],
                ["style", "height", '52px'],
                ["color", "color", 'rgba(34,174,255,1)'],
                ["style", "left", '12px'],
                ["style", "width", '40px']
            ],
            "${_ArrowDn}": [
                ["style", "top", '175px'],
                ["style", "height", '52px'],
                ["style", "opacity", '0'],
                ["style", "left", '650px'],
                ["style", "width", '42px']
            ],
            "${_Text5Copy}": [
                ["style", "top", '1px'],
                ["style", "width", '40px'],
                ["style", "height", '52px'],
                ["color", "color", 'rgba(34,174,255,1)'],
                ["style", "left", '12px'],
                ["style", "font-size", '42px']
            ],
            "${_Text2}": [
                ["style", "top", '96px'],
                ["style", "font-size", '18px'],
                ["color", "color", 'rgba(166,170,169,1.00)'],
                ["style", "height", '79px'],
                ["style", "opacity", '1'],
                ["style", "left", '-397px'],
                ["style", "width", '367px']
            ],
            "${_Stage}": [
                ["style", "height", '330px'],
                ["color", "background-color", 'rgba(255,255,255,1)'],
                ["style", "width", '830px']
            ],
            "${_Group}": [
                ["style", "top", '0px'],
                ["style", "left", '0px']
            ],
            "${_PlayButton}": [
                ["style", "top", '279px'],
                ["style", "cursor", 'pointer'],
                ["style", "height", '40px'],
                ["style", "opacity", '1'],
                ["style", "left", '395px'],
                ["style", "width", '40px']
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
            "${_LogoH2MDifuso}": [
                ["transform", "scaleX", '1'],
                ["style", "opacity", '1'],
                ["style", "left", '222px'],
                ["style", "top", '-20px']
            ],
            "${_Text5}": [
                ["style", "top", '1px'],
                ["style", "width", '40px'],
                ["style", "height", '52px'],
                ["color", "color", 'rgba(34,174,255,1.00)'],
                ["style", "left", '12px'],
                ["style", "font-size", '42px']
            ],
            "${_PDFFILE}": [
                ["style", "top", '57px'],
                ["style", "height", '91px'],
                ["style", "opacity", '0'],
                ["style", "left", '623px'],
                ["style", "width", '85px']
            ],
            "${_Group2}": [
                ["style", "top", '95px'],
                ["style", "opacity", '0'],
                ["style", "left", '21px']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 12789,
            autoPlay: true,
            timeline: [
                { id: "eid50", tween: [ "style", "${_Text2}", "left", '79px', { fromValue: '-397px'}], position: 0, duration: 2000 },
                { id: "eid94", tween: [ "style", "${_PDFFILE}", "opacity", '0', { fromValue: '0'}], position: 0, duration: 0, easing: "easeInQuint" },
                { id: "eid97", tween: [ "style", "${_PDFFILE}", "opacity", '1', { fromValue: '0.000000'}], position: 7500, duration: 3500, easing: "easeInQuint" },
                { id: "eid51", tween: [ "style", "${_Text2}", "top", '96px', { fromValue: '96px'}], position: 0, duration: 0 },
                { id: "eid90", tween: [ "style", "${_Computer}", "opacity", '1', { fromValue: '0'}], position: 0, duration: 6500, easing: "easeInQuint" },
                { id: "eid103", tween: [ "style", "${_ArrowDn}", "opacity", '0', { fromValue: '0'}], position: 0, duration: 0, easing: "easeInQuint" },
                { id: "eid106", tween: [ "style", "${_ArrowDn}", "opacity", '1', { fromValue: '0.000000'}], position: 9500, duration: 3289, easing: "easeInQuint" },
                { id: "eid54", tween: [ "style", "${_PlayButton}", "opacity", '0.33', { fromValue: '1'}], position: 0, duration: 402 },
                { id: "eid57", tween: [ "style", "${_Group2}", "opacity", '0.48961509146341', { fromValue: '0'}], position: 1250, duration: 750 },
                { id: "eid62", tween: [ "style", "${_Group2}", "opacity", '0.489615', { fromValue: '0.489615'}], position: 2000, duration: 0 },
                { id: "eid46", tween: [ "style", "${_LogoH2MDifuso}", "opacity", '0.08', { fromValue: '1'}], position: 0, duration: 2000 },
                { id: "eid34", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 0, duration: 0 },
                { id: "eid35", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 2000, duration: 0 },
                { id: "eid100", tween: [ "style", "${_PDFFILE}", "top", '227px', { fromValue: '57px'}], position: 7500, duration: 3500, easing: "easeInQuint" },
                { id: "eid60", trigger: [ function executeMediaFunction(e, data) { this._executeMediaAction(e, data); }, ['play', '${_SoundA2-12}', [] ], ""], position: 2000 }            ]
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
})(jQuery, AdobeEdge, "EDGE-43");
