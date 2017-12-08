/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='images/';

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
                rect: ['220px', '0px','860px','350px','auto', 'auto'],
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
                rect: ['397px', '274px','65px','65px','auto', 'auto'],
                cursor: ['pointer'],
                fill: ["rgba(0,0,0,0)",im+"PlayButton.png",'0px','0px']
            },
            {
                id: 'TextoB',
                type: 'text',
                rect: ['36px', '128px','498px','108px','auto', 'auto'],
                text: "Now you moved successfully your patient data to Health2Me and you are ready to share and collaborate!",
                align: "center",
                font: ['varela-round, sans-serif', 18, "rgba(166,170,169,1.00)", "100", "none", "normal"]
            },
            {
                id: 'TextoBCopy',
                type: 'text',
                rect: ['36px', '199px','498px','108px','auto', 'auto'],
                text: "Thank you for attending this interactive tutorial. Please stay tuned for new training material!",
                align: "center",
                font: ['varela-round, sans-serif', 18, "rgba(166,170,169,1.00)", "100", "none", "normal"]
            },
            {
                id: 'SoundA2-6B',
                display: 'none',
                type: 'audio',
                tag: 'audio',
                rect: ['128', '253','320px','45px','auto', 'auto'],
                source: ['media/SoundA2-6B.mp3']
            },
            {
                id: 'SoundA2-6',
                display: 'none',
                type: 'audio',
                tag: 'audio',
                rect: ['159', '182','320px','45px','auto', 'auto'],
                source: ['media/SoundA2-6.mp3']
            }],
            symbolInstances: [

            ]
        },
    states: {
        "Base State": {
            "${_TextoBCopy}": [
                ["style", "top", '199px'],
                ["style", "font-size", '18px'],
                ["style", "text-align", 'center'],
                ["color", "color", 'rgba(166,170,169,1)'],
                ["style", "height", '108px'],
                ["style", "opacity", '0.000000'],
                ["style", "left", '-377px'],
                ["style", "width", '498px']
            ],
            "${_Text}": [
                ["color", "color", 'rgba(34,174,255,1.00)'],
                ["style", "left", '36px'],
                ["style", "width", '554px'],
                ["style", "top", '40px'],
                ["style", "font-size", '26px'],
                ["style", "font-style", 'normal'],
                ["style", "font-family", 'varela-round, sans-serif'],
                ["style", "text-decoration", 'none'],
                ["style", "font-weight", '100']
            ],
            "${_LogoH2MDifuso}": [
                ["style", "top", '0px'],
                ["transform", "scaleX", '1'],
                ["style", "opacity", '1'],
                ["style", "left", '220px']
            ],
            "${_Stage}": [
                ["style", "height", '350px'],
                ["color", "background-color", 'rgba(255,255,255,1)'],
                ["style", "width", '860px']
            ],
            "${_PlayButton}": [
                ["style", "top", '274px'],
                ["style", "cursor", 'pointer'],
                ["style", "height", '65px'],
                ["style", "opacity", '1'],
                ["style", "left", '397px'],
                ["style", "width", '65px']
            ],
            "${_TextoB}": [
                ["style", "top", '128px'],
                ["style", "width", '498px'],
                ["style", "text-align", 'center'],
                ["style", "height", '108px'],
                ["color", "color", 'rgba(166,170,169,1)'],
                ["style", "opacity", '0'],
                ["style", "left", '-377px'],
                ["style", "font-size", '18px']
            ],
            "${_background1}": [
                ["style", "left", '57px'],
                ["style", "top", '-72px']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 18607,
            autoPlay: true,
            timeline: [
                { id: "eid220", tween: [ "style", "${_TextoBCopy}", "opacity", '1', { fromValue: '0.000000'}], position: 10000, duration: 1528 },
                { id: "eid86", tween: [ "style", "${_TextoB}", "left", '199px', { fromValue: '-377px'}], position: 0, duration: 2000 },
                { id: "eid34", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 0, duration: 0 },
                { id: "eid35", tween: [ "transform", "${_LogoH2MDifuso}", "scaleX", '1', { fromValue: '1'}], position: 2000, duration: 0 },
                { id: "eid82", tween: [ "style", "${_TextoB}", "opacity", '1', { fromValue: '0.000000'}], position: 0, duration: 2000 },
                { id: "eid54", tween: [ "style", "${_PlayButton}", "opacity", '0.33', { fromValue: '1'}], position: 0, duration: 402 },
                { id: "eid46", tween: [ "style", "${_LogoH2MDifuso}", "opacity", '0.08', { fromValue: '1'}], position: 0, duration: 2000 },
                { id: "eid219", tween: [ "style", "${_TextoBCopy}", "left", '199px', { fromValue: '-377px'}], position: 10000, duration: 1528 },
                { id: "eid221", trigger: [ function executeMediaFunction(e, data) { this._executeMediaAction(e, data); }, ['play', '${_SoundA2-6}', [] ], ""], position: 2000 },
                { id: "eid222", trigger: [ function executeMediaFunction(e, data) { this._executeMediaAction(e, data); }, ['play', '${_SoundA2-6B}', [] ], ""], position: 11527.604526108 }            ]
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
})(jQuery, AdobeEdge, "EDGE-17");
