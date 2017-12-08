<html lang="en" style="background: #F9F9F9;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Health2Me Technical Documentation</title>

    <!-- Core CSS - Include with every page -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="font-awesome/css/font-awesome.css" rel="stylesheet">-->
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="css/plugins/timeline/timeline.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <style type="text/css">
    ul {
        list-style-type: none;
    }   
    </style>
</head>
<body style= "font-family: Arial; 
background: #FFFFFF;">    
 <div id="wrapper">
     <? include "nav.html"; ?>     
     <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header  text-muted">PHP Coding standards</h1>
                <h4><p class="text-primary">Comments :</p></h4>	
					<p>
                        <ul>
                            <li>In: Variables coming in</li>
                            <li>Out: Variable returned from function</li>
                            <li>Desc: General description of what the function does</li>
                        </ul>
                    </p>


					<h4><p class="text-primary">Functions Name Outside of Classes:</p></h4>

                <p>
                    <ul>
                        <li>Lowercase with words underscore delimited</li>
                    <li>Abbreviations should be used sparingly so that code is readable.</li>
                    <li>Try to minimize letter count.</li>
                    <li>Try to minimize use of abbreviations.</li>
                    </ul>
                </p>
            
            <p>
                <ul><span style="color: #0b0;">Good:</span>
                    <li>'mcrypt_enc_self_test'</li>
                    <li>'mysql_list_fields'</li>
                </ul>   
            </p>
         
            <p>
                <ul>Ok:
                    <li>'mcrypt_module_get_algo_supported_key_sizes'</li>
                    <li>(could be 'mcrypt_mod_get_algo_sup_key_sizes'?)</li>
                    <li>'get_html_translation_table'</li>
                    <li>(could be 'html_get_trans_table'?)</li>
                </ul>   
            </p>
     
            <p>
                <ul>Bad:
                    <li>'hw_GetObjectByQueryCollObj'</li>
                    <li>'pg_setclientencoding'</li>
                    <li>'jf_n_s_i'</li>
                </ul>
            </p>
    
    <p class="text-info">Variable Names:</p>    
    <p>
        <ul>
            <li>Variable names should be meaningful.</li>
            <li>Variables should be lowercase and underscore delimited.</li>
        </ul>    
    </p>

    <h4><p class="text-primary">Class Names:</p></h4>
    <p>CampelCaps starting with a capital letter.</p>
    <p>
        <ul><span style="color: #0b0;">Good:</span>
            <li>'Curl'</li>
            <li>'FooBar'</li>
        </ul>
    </p>
    <p>
        <ul>Bad:
            <li>'foobar'</li>
            <li>‘foo_bar'</li>
        </ul>
    </p>

    <h4><p class="text-primary">Indentation:</p></h4>

    <p>Indentation is very important when it comes to team work.<br />
        XML style indentation(which can separate parent-children/siblings nodes clearly) is recommended.<br />
        
        <span style="color: #0b0;">e.g.:</span>
        <pre>
            <code>
        &lt;?php
            class Enum {
                protected $self = array();
                public function __construct( /*...*/ ) {
                    $args = func_get_args();
                    for( $i=0, $n=count($args); $i&lt;$n; $i++ )
                        $this-&gt;add($args[$i]);
                }

                public function __get( /*string*/ $name = null ) {
                    return $this-&gt;self[$name];
                }

                public function add( /*string*/ $name = null, /*int*/ $enum = null ) {
                    if( isset($enum) )
                        $this-&gt;self[$name] = $enum;
                    else
                        $this-&gt;self[$name] = end($this-&gt;self) + 1;
                }
            }

            class DefinedEnum extends Enum {
                public function __construct( /*array*/ $itms ) {
                    foreach( $itms as $name =&gt; $enum )
                        $this-&gt;add($name, $enum);
                }
            }

            class FlagsEnum extends Enum {
                public function __construct( /*...*/ ) {
                    $args = func_get_args();
                    for( $i=0, $n=count($args), $f=0x1; $i<$n; $i++, $f *= 0x2 )
                        $this-&gt;add($args[$i], $f);
                }
            }
        ?&gt;   
            </code>
        </pre>       
    </p>

    <h4><p class="text-primary">PHP Coding Style - OOP vs. Procedural:</p></h4>

    <p>
        <span style="color: #0b0;">OOP</span> is more modular approach and allows building reusable codes that can be shared among applications. These days more and more PHP APIs are follwing OOP style codes.
    </p>

    <p class="text-info">Advantages of each style:</p>
        
    <p>
        <ul><span style="color: #0b0;">OOP:</span>
            <li>There are many codes that could be shared and reused among forms.</li>
            <li>The data entry forms are anticipated to hange often over time.</li>
            <li>Many new data entry forms are anticipated to be added to the project over time.</li>
        </ul>
    </p>
    <p>
        <ul><span style="color: #0b0;">Procedural:</span>
            <li>The forms were very unique and few elements were shared.</li>
            <li>The forms were static and not expected to change much over time.</li>
            <li>None or only a few forms were expected to be added to the project over time.</li>
        </ul>
    </p>
<hr />
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
</div>
<!-- /.row -->
</div>
</body>
</html>    