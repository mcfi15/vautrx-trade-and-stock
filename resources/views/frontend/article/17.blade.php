@extends('layouts.app')

@section('title', ' Trading')

@section('content')
    <link rel="stylesheet" href="{{ asset('Public/static/css/article.css') }}" />
    <div class="">
        <main class="wrapper blog-page ">
            <!-- Account Heading Start -->
            <div class="page-top-banner">
                <div class="filter"
                    style="background-image: url('/Public/template/epsilon/img/redesign/slider/filter2-min.png');">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-md-8 mt-3">
                                <h1>Announcement on Launching Bug Bounty Program
                                </h1>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container p-t-30">
                <div class="row">
                    <div class="col-lg-12 card p-20">
                        <div>

                            <h1 class="f-s-24 f-w-600 m-b-15">
                               Announcement on Launching Bug Bounty Program
                            </h1>
                            <figure>
                                <img src="{{ asset('Upload/article/6529dfea9df1a.png') }}"
                                    style="width: 70px !important; height: 70px !important; max-width: 70px !important;">
                            </figure>
                            <div class="articletext">
                                <p><!-- Add any specific styles to your website's stylesheet or within a style block -->

                                    <head>
                                        <meta charset="UTF-8">
                                        <meta name="viewport" content="width=600px, ">
                                    </head>


                                    <style>
                                        .my-blog-post-container {
                                            display: flex;
                                            flex-direction: column;
                                            align-items: center;
                                            padding: 0px;
                                        }

                                        .my-blog-post-box {
                                            background-color: rgba(0, 0, 0, 0.01);
                                            border: 0px solid #e1e1e1;
                                            padding: 0px;
                                            border-radius: 5px;
                                            box-shadow: 0px 0px 10px 0px #00000010;
                                            text-align: left;
                                            margin: 10px;
                                            max-width: 1000px;
                                        }

                                        /* Add any other adjustments or specific styles you need */
                                        /* Centering tables */
                                        table {
                                            margin-left: auto;
                                            margin-right: auto;
                                        }
                                    </style>

                                    <!-- Here's the content you're inserting -->


                              <div class="my-blog-post-container">
  <div class="my-blog-post-box">
  <div class="my-blog-post-box">
 <p>
 Dear Users,
 </p>	
 <p>
 We are thrilled to launch our bug bounty program to make our crypto trading services more secure and reliable. We realize that even the most complete systems can still have vulnerabilities. Hence, we would like to encourage hackers to test and report any vulnerabilities on our platform by offering rewards. As a result, we can quickly address these issues and prevent any malicious attacks.
 </p></b><br>
 <p>
 Our bug bounty program is open to anyone who discovers vulnerabilities in our software. We will offer rewards for eligible vulnerability reports, with the amount ranging from $50 to $2,000 USD depending on the severity of the issue and the quality of the report.
 </p>
 </div>
 <div class="my-blog-post-box">
 <h3>
 Bug Severity & Reward
 </h3>
 <style>
  table, td, th {  border: 1px solid black;}table {  border-collapse: collapse;  width: 100%; height: 150px;}}
 </style>
 <table>
  <tbody>
   <tr>
     <tr>
     <th>Severity</th>
     <th>Reward</th>
   </tr>
    <td style="text-align: center;">
     Critical
    </td>
    <td style="text-align: center;">
     $1,000 - $2,000
    </td>
   </tr>
   <tr>
    <td style="text-align: center;">
     High
    </td>
    <td style="text-align: center;">
     $400 - $800
    </td>
    <tr>
     <td style="text-align: center;">
      Medium	
     </td>
     <td style="text-align: center;">
     $150 - $300
     </td>
    </tr>
    <tr>
     <td style="text-align: center;">
      Low
     </td>
     <td style="text-align: center;">
 $50 - $100
     </td>
    </tr>
    </tr>
   </tr>
  </tbody>
 </table>	
 </div>
 
 <div class="my-blog-post-box">
 <h3>
 Focus Area IN-SCOPE VULNERABILITIES (WEB, MOBILE)</h3>
 <p><li>
 Business logic issues
 </p></li>
 <p><li>
 Payments manipulation
 </p></li>
 <p><li>
 Remote code execution (RCE)
 </p></li>
 <p><li>
 Injection vulnerabilities (SQL, XXE)
 </p></li>
 <p><li>
 File inclusions (Local & Remote)
 </p></li>
 <p><li>
 Access Control Issues (IDOR, Privilege Escalation, etc)
 </p></li>
 <p><li>
 Leakage of sensitive information
 </p></li>
 <p><li>
 Server-Side Request Forgery (SSRF)
 </p></li>
 <p><li>
 Cross-Site Request Forgery (CSRF)
 </p></li>
 <p><li>
 Cross-Site Scripting (XSS)
 </p></li>
 <p><li>
 Directory traversal
 </p></li>
 <p><li>
 Other vulnerability with a clear potential loss
 </p></li>
 </div>
   
 <div class="my-blog-post-box">	
 <h3>
 OUT OF SCOPE: WEB VULNERABILITIES
 </h3>
 <p><li>
 Vulnerabilities found in out of scope resources are unlikely to be rewarded unless they present a serious business risk (at our sole discretion). In general, the following vulnerabilities do not correspond to the severity threshold:
 </p></li>
 <p><li>
 Vulnerabilities in third-party applications
 </p></li>
 <p><li>
 Assets that do not belong to the company
 </p></li>
 <p><li>
 Best practices concerns
 </p></li>
 <p><li>
 Recently (less than 30 days) disclosed 0day vulnerabilities
 </p></li>
 <p><li>
 Vulnerabilities affecting users of outdated browsers or platforms
 </p></li>
 <p><li>
 Social engineering, phishing, physical, or other fraud activities
 </p></li>
 <p><li>
 Publicly accessible login panels without proof of exploitation
 </p></li>
 <p><li>
 Reports that state that software is out of date/vulnerable without a proof of concept
 </p></li>
 <p><li>
 Reports that generated by scanners or any automated or active exploit tools</p></li>
 <p><li>
 Vulnerabilities involving active content such as web browser add-ons
 </p></li>
 <p><li>
 Most brute-forcing issues without clear impact
 </p></li>
 <p><li>
 Denial of service (DoS/DDoS)
 </p></li>
 <p><li>
 Theoretical issues
 </p></li>
 <p><li>
 Moderately Sensitive Information Disclosure
 </p></li>
 <p><li>
 Spam (sms, email, etc)</p></li>
 <p><li>
 Missing HTTP security headers
 </p></li>
 <p><li>
 Infrastructure vulnerabilities, including:
 </p></li>
 <style>
    .numbered-list {
      list-style: none;
      counter-reset: section;
    }

    .numbered-list li::before {
      content: "(" counter(section) ") ";
      counter-increment: section;
	  
    }
	
   .numbered-list {
      list-style: none;
      counter-reset: section;
      padding-left: 20px; 
    }
	
   
	
  </style>
  <ol class="numbered-list">
   <li> Certificates/TLS/SSL-related issues</li>
   <li> DNS issues (i.e. MX records, SPF records, DMARC records etc.)</li>
   <li> Server configuration issues (i.e., open ports, TLS, etc.)</li>
     <li>Open redirects</li>
   <li>Session fixation</li>
   <li> User account enumeration</li>
     <li>Clickjacking/Tapjacking and issues only exploitable through clickjacking/tap jacking</li>
   <li>Descriptive error messages (e.g. Stack Traces, application or server errors)</li>
   <li>Self-XSS that cannot be used to exploit other users</li>
     <li>Login & Logout CSRF</li>
   <li>Weak Captcha/Captcha Bypass</li>
   <li>Lack of Secure and HTTPOnly cookie flags</li>
     <li>Username/email enumeration via Login/Forgot Password Page error messages</li>
   <li> CSRF in forms that are available to anonymous users (e.g. the contact form)</li>
   <li>OPTIONS/TRACE HTTP method enabled</li>
     <li> Host header issues without proof-of-concept demonstrating the vulnerability</li>
   <li>Content spoofing and text injection issues without showing an attack vector/without being able to modify HTML/CSS</li>
   <li> Content Spoofing without embedded links/HTML</li>
     <li>Reflected File Download (RFD)</li>
   <li>Mixed HTTP Content</li>
   <li> HTTPS Mixed Content Scripts</li>
     <li>Manipulation with Password Reset Token</li>
   <li>MitM and local attacks</li>
 
 </ol> 	
 </div>
 
 <h3>
 Attacks requiring physical access to a user's device
 </h3>
 <p><li>
 Vulnerabilities that require root/jailbreak
 </p></li>
 <p><li>
 Vulnerabilities requiring extensive user interaction
 </p></li>
 <p><li>
 Exposure of non-sensitive data on the device
 </p></li>
 <p><li>
 Reports from static analysis of the binary without PoC that impacts business logic
 </p></li>
 <p><li>
 Lack of obfuscation/binary protection/root(jailbreak) detection
 </p></li>
 <p><li>
 Bypass certificate pinning on rooted devices
 </p></li>
 <p><li>
 ​Lack of Exploit mitigations i.e., PIE, ARC, or Stack anaries</p></li>
 <p><li>
 Sensitive data in URLs/request bodies when protected by TLS
 </p></li>
 <p><li>
 Path disclosure in the binary
 </p></li>
 <p><li>
 Sensitive information retained as plaintext in the device’s memory
 </p></li>
 <p><li>
 Crashes due to malformed URL Schemes or Intents sent to exported Activity/Service/Broadcast Receiver (exploiting these for sensitive data leakage is commonly in scope)
 </p></li>
 <p><li>
 Any kind of sensitive data stored in-app private directory
 </p></li>
 <p><li>
 Runtime hacking exploits using tools like but not limited to Frida/ Appmon (exploits only possible in a jailbroken environment)</p></li>
 <p><li>
 Shared links leaked through the system clipboard
 </p></li>
 <p><li>
 Any URIs leaked because a malicious app has permission to view URIs opened.
 </p></li>
 </div>
 <div class="my-blog-post-box">
 
 <h3>
 Program Rules
 </h3>
 <p><li>
 Avoid using web application scanners for automatic vulnerability searching which generates massive traffic
 </p></li>
 <p><li>
 Make every effort not to damage or restrict the availability of products, services, or infrastructure
 </p></li>
 <p><li>
 Avoid compromising any personal data, interruption, or degradation of any service
 </p></li>
 <p><li>
 Don’t access or modify other user data, localize all tests to your accounts
 </p></li>
 <p><li>
 Perform testing only within the scope
 </p></li>
 <p><li>
 Bypass certificate pinning on rooted devices
 </p></li>
 <p><li>
 Don’t exploit any DoS/DDoS vulnerabilities, social engineering attacks, or spam
 </p></li>
 <p><li>
 Don’t spam forms or account creation flows using automated scanners
 </p></li>
 <p><li>
 In case you find chain vulnerabilities we’ll pay only for vulnerability with the highest severity.
 </p></li>
 <p><li>
 Don’t break any law and stay in the defined scope
 </p></li>
 <p><li>
 Any details of found vulnerabilities must not be communicated to anyone who is not a member of our Team or an authorized employee of this Company without appropriate permission
 </p></li>	
 </div>
 
 
 <div class="my-blog-post-box">
 <h3>
 Disclosure Guidelines
 </h3>
 <p>
 We are happy to thank everyone who submits valid reports which help us improve the security. However, only those that meet the following eligibility requirements may receive a monetary reward:
 </p>
 <p><li>Do not discuss this program or any vulnerabilities (even resolved ones) outside of the program without express consent from the organization
 </p></li>
 <p><li>
 No vulnerability disclosure, including partial is allowed for the moment.
 </p></li>
 <p><li>
 Please do NOT publish/discuss bugs</p></li>
 
 </div>
 
 
 <div class="my-blog-post-box">
 
 <h3>
 Eligibility and Coordinated Disclosure
 </h3>
 <p>
 We are happy to thank everyone who submits valid reports which help us improve the security. However, only those that meet the following eligibility requirements may receive a monetary reward:
 </p>
 <p><li>
 You must be the first reporter of a vulnerability.</p></li>
 <p><li>
 The vulnerability must be a qualifying vulnerability
 </p></li>
 </p></li>
 <p><li>
 You must send a clear textual description of the report along with steps to reproduce the issue, include attachments such as screenshots or proof of concept code as necessary.
 </p></li>
 <p><li>
 You must not be a former or current employee of us or one of its contractor.</p></li>
 <p><li>
 You must not be a former or current employee of us or one of its contractor.</p></li>
 <p><li>
 Provide detailed but to-the point reproduction steps
 </p></li>
 <br><br>
 <p>
 </p>
 <p><b>
 Support Team
 </p></b>
 <p>
 April 25, 2023
 </p>
 
 </div></p></div>
                </div>
                </div>
            </div>
        </div>
    </main>
</div>


@endsection