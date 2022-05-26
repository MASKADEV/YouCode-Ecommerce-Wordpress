<h3 class="question-title">The "Add a New Legal Page" is blank</h3>
<div class="question-answer">
    <p>The most common causes for this error are:</p>
    <ul>
      <li>Other plugins that you use could block WP AutoTerms plugin from loading correctly (ie. the third-party plugin throws an error)</li>
      <li>The current theme you use loads JS scripts inside the WP Admin page and those scripts could block WP AutoTerms plugin from loading correctly (ie. the theme JS script throws an error)</li>
    </ul>
    <p>Here's how you can identify why the "Add a New Legal Page" is blank:</p>
    <ol>
      <li>Go to WP AutoTerms > Add a New Legal Page</li>
      <li>Right-click anywhere on the page (ie. empty space) and click "Inspect Element"</li>
      <li>Select the "Console" tab</li>
      <li>Refresh the page to make sure the error appears again</li>
    </ol>
    <p>If you do not have an "Inspect Element" menu item at the right-click menu, please follow these guidelines:</p>
    <ul>
      <li>Check that your web browser has a "Develop" menu item</li>
      <li>Try to use Google Chrome as the web browser for this purpose. Google Chrome has "Inspect Element" menu item added by default on the right-click menu.</li>
    </ul>
    <p>If the above steps do not work, please contact us and we'll troubleshoot the error.</p>
</div>

<h3 class="question-title">I've activated the plugin and a blank page appears on my website</h3>
<div class="question-answer">
    <p>This is a theme issue that can be easily fixed. It's related to how our plugin uses various HTML & CSS tags and how the theme is structured (ie. what HTML tags it uses).</p>
    <p>Here's how you can fix this:</p>
    <ol>
      <li>Go to WP AutoTerms > Compliance Kits > Links to Legal Pages kit.</li>
      <li>
        <p>Add the "Custom CSS" box, add the following code:</p>
        <code>
          .wpautoterms-footer {
            z-index: 0 !important;
            position: static !important;
          }
        </code>
      </li>
      <li>Click Save.</li>
    </ol>
    <p>If the above steps do not work, please contact us and we'll troubleshoot the error.</p>
</div>

<h3 class="question-title">The "Close" button on the Cookie Notice banner is not working</h3>
<div class="question-answer">
    <p>Various plugins that your website may use for caching & page speed optimization could prevent the WP AutoTerms plugin from working properly. An example of such plugin is "SG Optimizer".</p>
    <p>If your website uses caching & speed optimization plugins, the easiest workaround is this:</p>
    <ul>
      <li>
        <p>Exclude the WP AutoTerms JS scripts from being merged, deferred or minified in any way:</p>
        <p><pre>/wp-content/plugins/auto-terms-of-service-and-privacy-policy/js/base.js</pre></p>
        <p><pre>/wp-content/plugins/auto-terms-of-service-and-privacy-policy/js/wpautoterms.js</pre></p>
    </ul>
    <p>If the above steps do not work, please contact us and we'll troubleshoot the error.</p>
</div>

<h3 class="question-title">How can I include links to my legal pages in my website footer?</h3>
<div class="question-answer">
    <p>By default, links to published legal pages are <strong>automatically added</strong> to your website footer automatically through the "Links to Legal Pages" Compliance Kit.</p>
    <p>You can customize how the links to your legal pages look like by going to WP AutoTerms > Compliance Kits > Links to Legal Pages.</p>
</div>

<h3 class="question-title">How can I disable the links to my legal pages that have appeared in my website footer?</h3>
<div class="question-answer">
    <p>You can disable the links by disabling the "Links to Legal Pages" Compliance Kit.</p>
    <p>To do so, go to WP AutoTerms > Compliance Kits > Links to Legal Pages and disable it.</p>
</div>

<h3 class="question-title">How can I update the design of my legal page?</h3>
<div class="question-answer">
    <p>All legal pages generated with WP AutoTerms are using your current theme's `page.php` template.</p>
    <p>If you'd like to control the design of your legal page, you can:</p>
    <ol>
      <li>Create a new Page Template.<br /> <small>Please read <a href="https://developer.wordpress.org/themes/template-files-section/page-template-files/#creating-custom-page-templates-for-global-use">Creating Custom Page Templates for Global Use</a>.</small></li>
      <li>Assign the new Page Template by going to WP AutoTerms > All Legal Pages > Select your legal page > Select the new Page Template from the "Page Attributes" widget.</li>
    </ol>
</div>

<h3 class="question-title">How can I update the design of my legal pages archive template?</h3>
<div class="question-answer">
    <p>If you'd like to design the archive template file that contains all the generated legal pages, create a new file named `archive-wpautoterms_page.php`.</p>
</div>