<div class="wrap">
	<h2>Sentry Error Reporting Settings</h2>
	
	<h3>Core Settings</h3>
	
	<form action="" method="post">

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="sentry_dsn">Sentry DSN</label>
					</th>
					<td>
						<input name="sentry_dsn" type="text" id="sentry-dsn" value="<?php echo $settings['dsn']; ?>" class="regular-text">
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="sentry_reporting_level">Error Reporting Level</label>
					</th>
					<td>
						<select name="sentry_reporting_level">
							<?php foreach ($error_levels as $level => $int): ?>
							<option value="<?php echo $level; ?>" <?php echo ($settings['reporting_level'] == $level) ? 'selected="selected"' : '';?>><?php echo $level; ?></option>
							<?php endforeach; ?>
						</select><br/>
						 ("User Notices" is recommended)
					</td>
				</tr>
			</tbody>
		</table>

	<h3>Optional Settings</h3>
	
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="sentry_name">Server Hostname</label><br/>
					</th>
					<td colspan="4">
						<input name="sentry_name" type="text" id="sentry_name" value="<?php echo (isset($settings['name'])) ? $settings['name'] : ""; ?>" class="regular-text"> <br/>
						<span class="info">A string to override the default value for the server’s hostname. Defaults to <code>Raven_Compat::gethostname()</code>.</span>
					</td>
				</tr>
								
				<tr valign="top">
					<th scope="row">
						<label for="sentry_environment">Environment</label>
					</th>
					<td colspan="4">
						<input name="sentry_environment" type="text" id="sentry_environment" value="<?php echo (isset($settings['environment'])) ? $settings['environment'] : ""; ?>" class="regular-text" placeholder="production"> <br/>
						<span class="info">The environment your application is running in. </span>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">
						<label>Context options</label>
					</th>
					<td>
						<label for="sentry_user_context">User Context</label><br/>
						<textarea name="sentry_user_context" id="sentry_user_context" placeholder="email=foo@example.com" rows="5" cols="50"><?php echo (isset($context['user_context'])) ? $context['user_context'] : ""; ?></textarea> <br/>
						<span class="info">An array of tags to apply to events in this context. Syntax: <code>tag_name=value</code>. </span>
					</td>
					<td>
						<label for="sentry_tags_context">Tags Context</label><br/>
						<textarea name="sentry_tags_context" id="sentry_tags_context" placeholder="interesting=yes" rows="5" cols="50"><?php echo (isset($context['tags_context'])) ? $context['tags_context'] : ""; ?></textarea> <br/>
						<span class="info">An array of tags to apply to events in this context. Syntax: <code>tag_name=value</code>. </span>
					</td>
					<td>
						<label for="sentry_extra_context">Extra Context</label><br/>
						<textarea name="sentry_extra_context" id="sentry_extra_context" placeholder="happiness=very" rows="5" cols="50"><?php echo (isset($context['extra_context'])) ? $context['extra_context'] : ""; ?></textarea> <br/>
						<span class="info">An array of tags to apply to events in this context. Syntax: <code>tag_name=value</code>. </span>
					</td>
				</tr>
				
				<tr valign="top">
					<th>
						<label>Available tag functions</label>
					</th>
					<td colspan="4">
						<?php
							foreach($shortcodes as $shortcode){
								echo ($shortcode=='wp_current_user_id') ? 'id=' : '';
								echo ($shortcode=='wp_current_user_name') ? 'username=' : '';
								echo ($shortcode=='wp_current_user_email') ? 'email=' : '';
								echo "<code>[$shortcode]</code>";
								echo ($shortcode=='wp_bloginfo_url') ? ' (do not use the \'url\' tag. It is used by sentry already.)' : '';
								echo "<br>";
							}	
						?>
					</td>
				</tr>
				
				
				
			</tbody>
		</table>
	
	
	
	
	
	
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
		</p>
	
	</form>
	
	<div class="container">
		<h2>Some usage instructions & Examples</h2>
		<div class="highlight"><pre>
$sentryClient = WPSentry::getInstance();
$sentryClient->captureMessage('my log message');
$sentryClient->setRelease(MyApp::getReleaseVersion());
$sentryClient->setAppPath(app_root());
$sentryClient->setSendCallback(unction($data) {
    // dont send events if POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return false;
    }
});
$sentryClient->context->clear();

		</pre></div>
		
<a href="https://docs.sentry.io/hosted/clients/php/usage/">https://docs.sentry.io/hosted/clients/php/usage/</a><br/>

<div class="section" id="reporting-exceptions">
<h2>Reporting Exceptions<a class="headerlink" href="#reporting-exceptions" title="Permalink to this headline">¶</a></h2>
<p>If you want to report exceptions manually you can use the
<cite>captureException</cite> function.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="c1">// Basic Reporting</span>
<span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">captureException</span><span class="p">(</span><span class="nv">$ex</span><span class="p">);</span>

<span class="c1">// Provide some additional data with an exception</span>
<span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">captureException</span><span class="p">(</span><span class="nv">$ex</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span>
    <span class="s1">'extra'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span>
        <span class="s1">'php_version'</span> <span class="o">=&gt;</span> <span class="nb">phpversion</span><span class="p">()</span>
    <span class="p">),</span>
<span class="p">));</span>
</pre></div>
</div>
</div>
<div class="section" id="reporting-other-errors">
<h2>Reporting Other Errors<a class="headerlink" href="#reporting-other-errors" title="Permalink to this headline">¶</a></h2>
<p>Sometimes you don’t have an actual exception object, but something bad happened and you
want to report it anyways.  This is where <cite>captureMessage</cite> comes in.  It
takes a message and reports it to sentry.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="c1">// Capture a message</span>
<span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">captureMessage</span><span class="p">(</span><span class="s1">'my log message'</span><span class="p">);</span>
</pre></div>
</div>
<p>Note, <code class="docutils literal"><span class="pre">captureMessage</span></code> has a slightly different API than <code class="docutils literal"><span class="pre">captureException</span></code> to support
parameterized formatting:</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">captureMessage</span><span class="p">(</span><span class="s1">'my %s message'</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">'log'</span><span class="p">),</span> <span class="k">array</span><span class="p">(</span>
    <span class="s1">'extra'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span>
        <span class="s1">'foo'</span> <span class="o">=&gt;</span> <span class="s1">'bar'</span><span class="p">,</span>
    <span class="p">),</span>
<span class="p">));</span>
</pre></div>
</div>
</div>
<div class="section" id="optional-attributes">
<h2>Optional Attributes<a class="headerlink" href="#optional-attributes" title="Permalink to this headline">¶</a></h2>
<p>With calls to <code class="docutils literal"><span class="pre">captureException</span></code> or <code class="docutils literal"><span class="pre">captureMessage</span></code> additional data
can be supplied:</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">captureException</span><span class="p">(</span><span class="nv">$ex</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span>
    <span class="s1">'attr'</span> <span class="o">=&gt;</span> <span class="s1">'value'</span><span class="p">,</span>
<span class="p">));</span>
</pre></div>
</div>
<dl class="describe">
<dt>
<code class="descname">extra</code></dt>
<dd></dd></dl>

<p>Additional context for this event. Must be a mapping. Children can be any native JSON type.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="k">array</span><span class="p">(</span>
    <span class="s1">'extra'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span><span class="s1">'key'</span> <span class="o">=&gt;</span> <span class="s1">'value'</span><span class="p">)</span>
<span class="p">)</span>
</pre></div>
</div>
<dl class="describe">
<dt>
<code class="descname">fingerprint</code></dt>
<dd></dd></dl>

<p>The fingerprint for grouping this event.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="k">array</span><span class="p">(</span>
    <span class="s1">'fingerprint'</span> <span class="o">=&gt;</span> <span class="p">[</span><span class="s1">'{{ default }}'</span><span class="p">,</span> <span class="s1">'other value'</span><span class="p">]</span>
<span class="p">)</span>
</pre></div>
</div>
<dl class="describe">
<dt>
<code class="descname">level</code></dt>
<dd></dd></dl>

<p>The level of the event. Defaults to <code class="docutils literal"><span class="pre">error</span></code>.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="k">array</span><span class="p">(</span>
    <span class="s1">'level'</span> <span class="o">=&gt;</span> <span class="s1">'warning'</span>
<span class="p">)</span>
</pre></div>
</div>
<p>Sentry is aware of the following levels:</p>
<ul class="simple" style="margin-left: 18px; list-style: square;">
<li><code>debug</code> (the least serious)</li>
<li><code>info</code></li>
<li><code>warning</code></li>
<li><code>error</code></li>
<li><code>fatal</code> (the most serious)</li>
</ul>
<dl class="describe">
<dt>
<code class="descname">logger</code></dt>
<dd></dd></dl>

<p>The logger name for the event.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="k">array</span><span class="p">(</span>
    <span class="s1">'logger'</span> <span class="o">=&gt;</span> <span class="s1">'default'</span>
<span class="p">)</span>
</pre></div>
</div>
<dl class="describe">
<dt>
<code class="descname">tags</code></dt>
<dd></dd></dl>

<p>Tags to index with this event. Must be a mapping of strings.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="k">array</span><span class="p">(</span>
    <span class="s1">'tags'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span><span class="s1">'key'</span> <span class="o">=&gt;</span> <span class="s1">'value'</span><span class="p">)</span>
<span class="p">)</span>
</pre></div>
</div>
<dl class="describe">
<dt>
<code class="descname">user</code></dt>
<dd></dd></dl>

<p>The acting user.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="k">array</span><span class="p">(</span>
    <span class="s1">'user'</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span>
        <span class="s1">'id'</span> <span class="o">=&gt;</span> <span class="mi">42</span><span class="p">,</span>
        <span class="s1">'email'</span> <span class="o">=&gt;</span> <span class="s1">'clever-girl'</span>
    <span class="p">)</span>
<span class="p">)</span>
</pre></div>
</div>
</div>
<div class="section" id="getting-back-an-event-id">
<h2>Getting Back an Event ID<a class="headerlink" href="#getting-back-an-event-id" title="Permalink to this headline">¶</a></h2>
<p>An event id is a globally unique id for the event that was just sent. This
event id can be used to find the exact event from within Sentry.</p>
<p>This is often used to display for the user and report an error to customer
service.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">getLastEventID</span><span class="p">();</span>
</pre></div>
</div>
</div>		
<div class="section" id="breadcrumbs">
<h2>Breadcrumbs<a class="headerlink" href="#breadcrumbs" title="Permalink to this headline">¶</a></h2>
<p>Sentry supports capturing breadcrumbs – events that happened prior to an issue.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">breadcrumbs</span><span class="o">-&gt;</span><span class="na">record</span><span class="p">(</span><span class="k">array</span><span class="p">(</span>
    <span class="s1">'message'</span> <span class="o">=&gt;</span> <span class="s1">'Authenticating user as '</span> <span class="o">.</span> <span class="nv">$username</span><span class="p">,</span>
    <span class="s1">'data'</span> <span class="o">=&gt;</span> <span class="nv">$username</span><span class="p">,</span>
    <span class="s1">'category'</span> <span class="o">=&gt;</span> <span class="s1">'auth'</span><span class="p">,</span>
    <span class="s1">'level'</span> <span class="o">=&gt;</span> <span class="s1">'info'</span><span class="p">,</span>
<span class="p">));</span>
</pre></div>
</div>
</div>
<div class="section" id="filtering-out-errors">
<h2>Filtering Out Errors<a class="headerlink" href="#filtering-out-errors" title="Permalink to this headline">¶</a></h2>
<p>Its common that you might want to prevent automatic capture of certain areas. Ideally you simply would avoid calling out to Sentry in that case, but that’s often easier said than done. Instead, you can provide a function which the SDK will call before it sends any data, allowing you both to mutate that data, as well as prevent it from being sent to the server.</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">setSendCallback</span><span class="p">(</span><span class="k">function</span><span class="p">(</span><span class="nv">$data</span><span class="p">)</span> <span class="p">{</span>
    <span class="nv">$ignore_types</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span><span class="s1">'Symfony\Component\HttpKernel\Exception\NotFoundHttpException'</span><span class="p">);</span>

    <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$data</span><span class="p">[</span><span class="s1">'exception'</span><span class="p">]</span> <span class="o">&amp;&amp;</span> <span class="nb">in_array</span><span class="p">(</span><span class="nv">$data</span><span class="p">[</span><span class="s1">'exception'</span><span class="p">][</span><span class="s1">'values'</span><span class="p">][</span><span class="mi">0</span><span class="p">][</span><span class="s1">'type'</span><span class="p">],</span> <span class="nv">$ignore_types</span><span class="p">)</span>
    <span class="p">{</span>
        <span class="k">return</span> <span class="k">false</span><span class="p">;</span>
    <span class="p">}</span>
<span class="p">});</span>
</pre></div>
</div>
</div>
<div class="section" id="error-control-operators">
<h2>Error Control Operators<a class="headerlink" href="#error-control-operators" title="Permalink to this headline">¶</a></h2>
<p>In PHP its fairly common to use the <a class="reference external" href="http://php.net/manual/en/language.operators.errorcontrol.php">suppression operator</a>
to avoid bubbling up handled errors:</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="nv">$my_file</span> <span class="o">=</span> <span class="o">@</span><span class="nb">file</span><span class="p">(</span><span class="s1">'non_existent_file'</span><span class="p">);</span>
</pre></div>
</div>
<p>In these situations, Sentry will never capture the error. If you wish to capture it at that stage
you’d need to manually call out to the PHP client:</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="nv">$my_file</span> <span class="o">=</span> <span class="o">@</span><span class="nb">file</span><span class="p">(</span><span class="s1">'non_existent_file'</span><span class="p">);</span>
<span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="nv">$my_file</span><span class="p">)</span> <span class="p">{</span>
    <span class="c1">// ...</span>
    <span class="nv">$sentryClient</span><span class="o">-&gt;</span><span class="na">captureLastError</span><span class="p">();</span>
<span class="p">}</span>
</pre></div>
</div>
</div>



	</div>

</div>