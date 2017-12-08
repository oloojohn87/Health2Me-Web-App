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
                rect: ['36px', '104px','367px','108px','auto', 'auto'],
                text: "Review the reports and choose an appropriate date for them. Click “next” or swipe to move from one report to another, and click on “Finish” when done.<br>",
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
                    text: "E",
                    align: "left",
                    font: ['varela-round, sans-serif', 42, "rgba(34,174,255,1.00)", "100", "none", "normal"]
                }]
            },
            {
                id: 'SoundA2-5',
                type: 'audio',
                tag: 'audio',
                rect: ['21', '331','320px','45px','auto', 'auto'],
                source: ['/TutorialBox/media/SoundA2-5.mp3']
            },
            {
                id: 'ClasReports',
                type: 'image',
                rect: ['476px', '14px','337px','301px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"ClasReports.png",'0px','0px']
            },
            {
                id: 'Ellipse2',
                type: 'ellipse',
                rect: ['577px', '73px','126px','120px','auto', 'auto'],
                borderRadius: ["50%", "50%", "50%", "50%"],
                fill: ["rgba(172,224,255,1)"],
                stroke: [5,"rgba(84,188,0,1.00)","solid"]
            },
            {
                id: 'ArrowDnLeft',
                type: 'image',
                rect: ['739px', '168px','65px','72px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"ArrowDnLeft.png",'0px','0px']
            }],
            symbolInstances: [

            ]
        },
    states: {
        "Base State": {
            "${_Ellipse2}": [
                ["color", "background-color", 'rgba(172,224,255,0.00)'],
                ["style", "border-style", 'solid'],
                ["style", "left", '577px'],
                ["style", "width", '126px'],
                ["style", "top", '73px'],
                ["style", "height", '120px'],
                ["color", "border-color", 'rgba(84,188,0,1.00)'],
                ["style", "border-width", '5px'],
                ["style", "opacity", '0']
            ],
            "${_GroupCopy3}": [
                ["style", "top", '0px'],
                ["style", "left", '0px']
            ],
            "${_background1}": [
                ["style", "left", '57px'],
                ["style", "top", '-72px']
            ],
            "${_Text5Copy3}": [
                ["style", "top", '2px'],
                ["style", "font-size", '42px'],
                ["style", "height", '52px'],
                ["color", "color", 'rgba(34,174,255,1)'],
                ["style", "left", '10px'],
                ["style", "width", '40px']
            ],
            "${_EllipseCopy3}": [
                ["color", "background-color", 'rgba(172,224,255,1)'],
                ["style", "left", '0px'],
                ["style", "top", '0px']
            ],
            "${_TextoB}": [
                ["style", "top", '96px'],
                ["style", "font-size", '18px'],
                ["color", "color", 'rgba(166,170,169,1)'],
                ["style", "height", '108px'],
                ["style", "opacity", '0'],
                ["style", "left", '-377px'],
                ["style", "width", '367px']
            ],
            "${_PlayButton}": [
                ["style", "top", '279px'],
                ["style", "left", '395px'],
                ["style", "height", '40px'],
                ["style", "opacity", '1'],
                ["style", "cursor", 'pointer'],
                ["style", "width", '40px']
            ],
            "${_ClasReports}": [
                ["style", "top", '14px'],
                ["style", "height", '301px'],
                ["style", "opacity", '0'],
                ["style", "left", '476px'],
                ["style", "width", '337px']
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
            "${_GrupoRotuloB}": [
                ["style", "top", '95px'],
                ["style", "opacity", '0.02'],
                ["style", "left", '21px']
            ],
            "${_ArrowDnLeft}": [
                ["style", "top", '165px'],
                ["style", "height", '72px'],
                ["style", "opacity", '0'],
                ["style", "left", '719px'],
                ["style", "width", '65px']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 12214,
            autoPlay: true,
            timeline: [
                { id: "eid204", tween: [ "style", "${_ClasReports}", "opacity", '0', { fromValue: '0'}], position: 0, duration: 0 },
                { id: "eid207", tween: [ "style", "${_ClasReports}", "opacity", '1', { fromValue: '0.000000'}], position: 1358, duration: 1465 },
                { id: "eid209", tween: [ "style", "${_ClasReports}", "opacity", '1', { fromValue: '1'}], position: 12214, duration: 0 },
                { id: "eid92", tween: [ "style", "${_GrupoRotuloB}", "opacity", '0.489615', { fromValue: '0.02'}], position: 0, duration: 2000 },
                { id: "eid34", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 0, duration: 0 },
                { id: "eid35", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 2000, duration: 0 },
                { id: "eid192", tween: [ "color", "${_Ellipse2}", "background-color", 'rgba(172,224,255,0.00)', { animationColorSpace: 'RGB', valueTemplate: undefined, fromValue: 'rgba(172,224,255,0.00)'}], position: 4214, duration: 0 },
                { id: "eid82", tween: [ "style", "${_TextoB}", "opacity", '1', { fromValue: '0.000000'}], position: 0, duration: 2000 },
                { id: "eid167", tween: [ "style", "${_ArrowDnLeft}", "opacity", '0', { fromValue: '0'}], position: 0, duration: 0 },
                { id: "eid168", tween: [ "style", "${_ArrowDnLeft}", "opacity", '0', { fromValue: '0'}], position: 4000, duration: 0 },
                { id: "eid173", tween: [ "style", "${_ArrowDnLeft}", "opacity", '0.24', { fromValue: '1'}], position: 6500, duration: 500 },
                { id: "eid174", tween: [ "style", "${_ArrowDnLeft}", "opacity", '1', { fromValue: '0.24'}], position: 7000, duration: 500 },
                { id: "eid175", tween: [ "style", "${_ArrowDnLeft}", "opacity", '0.25', { fromValue: '1'}], position: 7500, duration: 500 },
                { id: "eid176", tween: [ "style", "${_ArrowDnLeft}", "opacity", '1', { fromValue: '0.25'}], position: 8000, duration: 500 },
                { id: "eid188", tween: [ "style", "${_ArrowDnLeft}", "opacity", '0', { fromValue: '1'}], position: 10750, duration: 500 },
                { id: "eid189", tween: [ "style", "${_ArrowDnLeft}", "opacity", '1', { fromValue: '0'}], position: 11250, duration: 500 },
                { id: "eid162", tween: [ "style", "${_ArrowDnLeft}", "opacity", '1', { fromValue: '1'}], position: 12214, duration: 0 },
                { id: "eid54", tween: [ "style", "${_PlayButton}", "opacity", '0.33', { fromValue: '1'}], position: 0, duration: 402 },
                { id: "eid225", tween: [ "style", "${_ArrowDnLeft}", "left", '719px', { fromValue: '719px'}], position: 6591, duration: 0 },
                { id: "eid184", tween: [ "style", "${_ArrowDnLeft}", "left", '773px', { fromValue: '739px'}], position: 10483, duration: 17 },
                { id: "eid195", tween: [ "style", "${_Ellipse2}", "opacity", '1', { fromValue: '0'}], position: 3136, duration: 1078 },
                { id: "eid214", tween: [ "style", "${_Ellipse2}", "opacity", '0', { fromValue: '1'}], position: 4750, duration: 159 },
                { id: "eid215", tween: [ "style", "${_Ellipse2}", "opacity", '1', { fromValue: '0'}], position: 4909, duration: 168 },
                { id: "eid216", tween: [ "style", "${_Ellipse2}", "opacity", '0', { fromValue: '1'}], position: 5077, duration: 173 },
                { id: "eid217", tween: [ "style", "${_Ellipse2}", "opacity", '1', { fromValue: '0'}], position: 5250, duration: 136 },
                { id: "eid198", tween: [ "style", "${_Ellipse2}", "opacity", '0', { fromValue: '1'}], position: 6250, duration: 341 },
                { id: "eid46", tween: [ "style", "${_LogoH2MDifuso}", "opacity", '0.08', { fromValue: '1'}], position: 0, duration: 2000 },
                { id: "eid226", tween: [ "style", "${_ArrowDnLeft}", "top", '165px', { fromValue: '165px'}], position: 6591, duration: 0 },
                { id: "eid183", tween: [ "style", "${_ArrowDnLeft}", "top", '232px', { fromValue: '168px'}], position: 10483, duration: 17 },
                { id: "eid86", tween: [ "style", "${_TextoB}", "left", '90px', { fromValue: '-377px'}], position: 0, duration: 2000 },
                { id: "eid190", trigger: [ function executeMediaFunction(e, data) { this._executeMediaAction(e, data); }, ['play', '${_SoundA2-5}', [] ], ""], position: 2000 }            ]
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
})(jQuery, AdobeEdge, "EDGE-47");
