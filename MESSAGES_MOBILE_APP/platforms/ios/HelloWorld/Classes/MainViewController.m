/*
 Licensed to the Apache Software Foundation (ASF) under one
 or more contributor license agreements.  See the NOTICE file
 distributed with this work for additional information
 regarding copyright ownership.  The ASF licenses this file
 to you under the Apache License, Version 2.0 (the
 "License"); you may not use this file except in compliance
 with the License.  You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing,
 software distributed under the License is distributed on an
 "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 KIND, either express or implied.  See the License for the
 specific language governing permissions and limitations
 under the License.
 */

//
//  MainViewController.h
//  HelloWorld
//
//  Created by ___FULLUSERNAME___ on ___DATE___.
//  Copyright ___ORGANIZATIONNAME___ ___YEAR___. All rights reserved.
//

#import "MainViewController.h"
#import <Parse/Parse.h>

@implementation MainViewController

@synthesize timer, webView, currentUser, internetOn, alreadyLoaded;

- (id)initWithNibName:(NSString*)nibNameOrNil bundle:(NSBundle*)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Uncomment to override the CDVCommandDelegateImpl used
        // _commandDelegate = [[MainCommandDelegate alloc] initWithViewController:self];
        // Uncomment to override the CDVCommandQueue used
        // _commandQueue = [[MainCommandQueue alloc] initWithViewController:self];
        self.alreadyLoaded = false;
    }
    return self;
}

- (id)init
{
    self = [super init];
    if (self) {
        // Uncomment to override the CDVCommandDelegateImpl used
        // _commandDelegate = [[MainCommandDelegate alloc] initWithViewController:self];
        // Uncomment to override the CDVCommandQueue used
        // _commandQueue = [[MainCommandQueue alloc] initWithViewController:self];
        self.alreadyLoaded = false;
    }
    return self;
}

- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];

    // Release any cached data, images, etc that aren't in use.
}

#pragma mark View lifecycle

- (void)viewWillAppear:(BOOL)animated
{
    // View defaults to full size.  If you want to customize the view's size, or its subviews (e.g. webView),
    // you can do so here.

    [super viewWillAppear:animated];
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}

- (void)viewWillDisappear:(BOOL)animated
{
    NSLog(@"View Disappeared");
    [super viewWillDisappear:animated];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return [super shouldAutorotateToInterfaceOrientation:interfaceOrientation];
}

/* Comment out the block below to over-ride */

/*
- (UIWebView*) newCordovaViewWithFrame:(CGRect)bounds
{
    return[super newCordovaViewWithFrame:bounds];
}
*/

#pragma mark UIWebDelegate implementation

- (void)enteringBackground
{
    NSLog(@"Entering Background");
    [self.timer invalidate];
    self.timer = nil;
    [self update:nil];
    NSString *jsString = @"function e() {return document.getElementById('rememberMe').value;}e();";
    NSString *responseString = [self.webView stringByEvaluatingJavaScriptFromString:jsString];
    if([responseString compare:@"0"] == NSOrderedSame && [self.currentUser compare:@"-1"] != NSOrderedSame)
    {
        PFInstallation *currentInstallation = [PFInstallation currentInstallation];
        [currentInstallation removeObject:[@"C-" stringByAppendingString:self.currentUser] forKey:@"channels"];
        [currentInstallation saveInBackground];
    }
}
- (void)enteringForeground
{
    NSLog(@"Entering Foreground");
    self.timer = [NSTimer scheduledTimerWithTimeInterval:5.0 target:self selector:@selector(update:) userInfo:self.webView repeats:YES];
}

-(BOOL)reachable
{
    Reachability *r = [Reachability reachabilityWithHostName:@"google.com"];
    NetworkStatus internetStatus = [r currentReachabilityStatus];
    if(internetStatus == NotReachable) {
        return NO;
    }
    return YES;
}

- (void)reachabilityChanged:(NSNotification *)note
{
    Reachability* curReach = [note object];
    NSParameterAssert([curReach isKindOfClass:[Reachability class]]);
    [self updateInterfaceWithReachability:curReach];
}

- (void)updateInterfaceWithReachability:(Reachability *)reachability
{
    [self update:nil];
    if (reachability == self.internetReachability)
    {
        
        if([reachability currentReachabilityStatus] == NotReachable && self.internetOn == true)
        {
            self.internetOn = false;
            NSString *jsString2 = @"$.mobile.changePage( \"#no_internet_page\", { changeHash: true });";
            [self.webView stringByEvaluatingJavaScriptFromString:jsString2];
        }
        else if([reachability currentReachabilityStatus] != NotReachable && self.internetOn == false)
        {
            self.internetOn = true;
            NSString *jsString2;
            if([self.currentUser compare:@"-1"] == NSOrderedSame)
                jsString2 = @"$.mobile.changePage( \"#signin_page\", { changeHash: true });";
            else
                jsString2 = @"$.mobile.changePage( \"#main_page\", { changeHash: true });";
            [self.webView stringByEvaluatingJavaScriptFromString:jsString2];
        }
    }
}

- (void)update:(NSTimer *)t
{
    NSLog(@"Checking...");
    
    NSString *jsString = @"function f() {return document.getElementById('userid').value;}f();";
    
    NSString *responseString = [self.webView stringByEvaluatingJavaScriptFromString:jsString];
    if([self.currentUser compare:@"-1"] == NSOrderedSame)
    {
        if([responseString compare:@"-1"] != NSOrderedSame)
        {
            // subscribe to responseString channel
            PFInstallation *currentInstallation = [PFInstallation currentInstallation];
            [currentInstallation addUniqueObject:[@"C-" stringByAppendingString:responseString] forKey:@"channels"];
            [currentInstallation saveInBackground];
        }
    }
    else
    {
        if([responseString compare:@"-1"] != NSOrderedSame)
        {
            if([self.currentUser compare:responseString] != NSOrderedSame)
            {
                // unsubscribe to current channel and subscribe to responseString channel
                PFInstallation *currentInstallation = [PFInstallation currentInstallation];
                [currentInstallation removeObject:[@"C-" stringByAppendingString:self.currentUser] forKey:@"channels"];
                [currentInstallation addUniqueObject:[@"C-" stringByAppendingString:responseString] forKey:@"channels"];
                [currentInstallation saveInBackground];
            }
        }
        else
        {
            PFInstallation *currentInstallation = [PFInstallation currentInstallation];
            [currentInstallation removeObject:[@"C-" stringByAppendingString:self.currentUser] forKey:@"channels"];
            [currentInstallation saveInBackground];
        }
    }
    self.currentUser = responseString;
    
    NSLog(@"%@", self.currentUser);
}

- (void)webViewDidFinishLoad:(UIWebView*)theWebView
{
    // Black base color for background matches the native apps
    if(!self.alreadyLoaded)
    {
        self.alreadyLoaded = true;
        [theWebView setFrame:CGRectMake(0, 20, theWebView.frame.size.width, theWebView.frame.size.height - 20)];
        theWebView.backgroundColor = [UIColor blackColor];
        self.webView = theWebView;
        self.currentUser = @"-1";
        self.internetOn = true;
        
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachabilityChanged:) name:kReachabilityChangedNotification object:nil];
        
        self.internetReachability = [Reachability reachabilityForInternetConnection];
        [self.internetReachability startNotifier];
        [self updateInterfaceWithReachability:self.internetReachability];
        
        
        NSString *jsString = @"function f() {return document.getElementById('userid').value}f();";
        
        NSString *responseString = [theWebView stringByEvaluatingJavaScriptFromString:jsString];
        NSLog(@"%@", responseString);
        
        self.timer = [NSTimer scheduledTimerWithTimeInterval:5.0 target:self selector:@selector(update:) userInfo:theWebView repeats:YES];
    }

    return [super webViewDidFinishLoad:theWebView];
}

-(void)dealloc
{
    NSLog(@"Calling Dealloc");
    [[NSNotificationCenter defaultCenter] removeObserver:self name:kReachabilityChangedNotification object:nil];
}

/* Comment out the block below to over-ride */

/*

- (void) webViewDidStartLoad:(UIWebView*)theWebView
{
    return [super webViewDidStartLoad:theWebView];
}

- (void) webView:(UIWebView*)theWebView didFailLoadWithError:(NSError*)error
{
    return [super webView:theWebView didFailLoadWithError:error];
}

- (BOOL) webView:(UIWebView*)theWebView shouldStartLoadWithRequest:(NSURLRequest*)request navigationType:(UIWebViewNavigationType)navigationType
{
    return [super webView:theWebView shouldStartLoadWithRequest:request navigationType:navigationType];
}
*/

@end

@implementation MainCommandDelegate

/* To override the methods, uncomment the line in the init function(s)
   in MainViewController.m
 */

#pragma mark CDVCommandDelegate implementation

- (id)getCommandInstance:(NSString*)className
{
    return [super getCommandInstance:className];
}

/*
   NOTE: this will only inspect execute calls coming explicitly from native plugins,
   not the commandQueue (from JavaScript). To see execute calls from JavaScript, see
   MainCommandQueue below
*/
- (BOOL)execute:(CDVInvokedUrlCommand*)command
{
    return [super execute:command];
}

- (NSString*)pathForResource:(NSString*)resourcepath;
{
    return [super pathForResource:resourcepath];
}

@end

@implementation MainCommandQueue

/* To override, uncomment the line in the init function(s)
   in MainViewController.m
 */
- (BOOL)execute:(CDVInvokedUrlCommand*)command
{
    return [super execute:command];
}

@end
