/***********************
* Adobe Edge Composition Actions
*
* Edit this file with caution, being careful to preserve 
* function signatures and comments starting with 'Edge' to maintain the 
* ability to interact with these actions from within Adobe Edge
*
***********************/
(function($, Edge, compId){
var Composition = Edge.Composition, Symbol = Edge.Symbol; // aliases for commonly used Edge classes
//Edge symbol: 'stage'
(function(symbolName) {









      

      Symbol.bindTriggerAction(compId, symbolName, "Default Timeline", 0, function(sym, e) {
         sym.stop();

      });
      //Edge binding end

      Symbol.bindElementAction(compId, symbolName, "${_PlayButton}", "click", function(sym, e) {
         sym.play();
         

      });
      //Edge binding end

})("stage");
   //Edge symbol end:'stage'

})(jQuery, AdobeEdge, "EDGE-43");