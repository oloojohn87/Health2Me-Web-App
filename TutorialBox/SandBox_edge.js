/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='TutorialBox/images/';

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
                id: 'Text',
                type: 'text',
                rect: ['84px', '84px','257px','33px','auto', 'auto'],
                text: "Health2Me.- Instant Tutorial Box",
                font: ['varela-round, sans-serif', 24, "rgba(0,0,0,1)", "100", "none", "normal"]
            },
            {
                id: 'Text2',
                type: 'text',
                rect: ['36px', '104px','367px','79px','auto', 'auto'],
                text: "Welcome to our on-screen animated tutorial system. Designed to be easy, fast and convenient.",
                align: "left",
                font: ['varela-round, sans-serif', 18, "rgba(166,170,169,1.00)", "100", "none", "normal"]
            },
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
            }],
            symbolInstances: [

            ]
        },
    states: {
        "Base State": {
            "${_background1}": [
                ["style", "left", '57px'],
                ["style", "top", '-72px']
            ],
            "${_LogoH2MDifuso}": [
                ["style", "left", '220px'],
                ["style", "top", '0px']
            ],
            "${_Stage}": [
                ["style", "height", '350px'],
                ["color", "background-color", 'rgba(255,255,255,1)'],
                ["style", "width", '860px']
            ],
            "${_Text}": [
                ["subproperty", "textShadow.blur", '10px'],
                ["subproperty", "textShadow.offsetH", '4px'],
                ["color", "color", 'rgba(34,174,255,1.00)'],
                ["subproperty", "textShadow.offsetV", '4px'],
                ["style", "left", '36px'],
                ["style", "width", '554px'],
                ["style", "top", '40px'],
                ["style", "font-size", '26px'],
                ["subproperty", "textShadow.color", 'rgba(166,162,162,1.00)'],
                ["style", "font-style", 'normal'],
                ["style", "font-family", 'varela-round, sans-serif'],
                ["style", "text-decoration", 'none'],
                ["style", "font-weight", '100']
            ],
            "${_Text2}": [
                ["style", "top", '104px'],
                ["style", "font-size", '18px'],
                ["color", "color", 'rgba(166,170,169,1.00)'],
                ["style", "height", '79px'],
                ["style", "left", '36px'],
                ["style", "width", '367px']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 3164,
            autoPlay: true,
            timeline: [
                { id: "eid18", tween: [ "style", "${_Text}", "width", '554px', { fromValue: '554px'}], position: 3164, duration: 0 },
                { id: "eid10", tween: [ "subproperty", "${_Text}", "textShadow.offsetH", '4px', { fromValue: '4px'}], position: 0, duration: 0 },
                { id: "eid14", tween: [ "style", "${_Text}", "font-size", '26px', { fromValue: '26px'}], position: 3164, duration: 0 },
                { id: "eid15", tween: [ "color", "${_Text}", "color", 'rgba(34,174,255,1.00)', { animationColorSpace: 'RGB', valueTemplate: undefined, fromValue: 'rgba(34,174,255,1.00)'}], position: 3164, duration: 0 },
                { id: "eid8", tween: [ "subproperty", "${_Text}", "textShadow.blur", '72px', { fromValue: '10px'}], position: 0, duration: 2299 },
                { id: "eid13", tween: [ "subproperty", "${_Text}", "textShadow.blur", '10px', { fromValue: '72px'}], position: 2299, duration: 865 },
                { id: "eid9", tween: [ "subproperty", "${_Text}", "textShadow.color", 'rgba(166,162,162,1.00)', { fromValue: 'rgba(166,162,162,1.00)'}], position: 0, duration: 0 },
                { id: "eid20", tween: [ "style", "${_Text}", "left", '36px', { fromValue: '36px'}], position: 3164, duration: 0 },
                { id: "eid11", tween: [ "subproperty", "${_Text}", "textShadow.offsetV", '4px', { fromValue: '4px'}], position: 0, duration: 0 },
                { id: "eid19", tween: [ "style", "${_Text}", "top", '40px', { fromValue: '40px'}], position: 3164, duration: 0 }            ]
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
})(jQuery, AdobeEdge, "EDGE-455333329");
