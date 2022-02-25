<?php
// .US
$ext = $this->session->userdata('tld'); ?>
<section id="pricing" class="bg-silver-light">
    <div class="container">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12">

                    <section class="panel panel-default">
                        <header class="panel-heading text-center">
                            <h3><?= lang('additional_information').' '.'('.$ext.')'?></h3>
                        </header>

                        <div class="panel-body" style="padding:30px;">

                            <?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'cart/domain_fields',$attributes); ?>

                            <div class="row">
                                <div class="col-md-7">

                                        <?php
            if($this->session->userdata('transfer')){ ?>
                                        <div class="form-group">
                                            <label
                                                class="label-control"><?=lang('domain_transfer')." ".lang('authcode')?>
                                                (<?=lang('authcode_requirement')?>)</label>
                                            <input name="authcode" type="text" class="form-control">
                                            <p><?=lang('auth_epp_required_list')?> .com, .net, .org, .us, .biz, .info,
                                                .me, .co, .io, .ca, .tv, .in, .mobi, .cc, .pe, .com.pe, .net.pe,
                                                .org.pe,.tech, .top, .party, .loan, .faith and other new gTLD/country
                                                code TLD domain transfers</p>
                                        </div>
                                        <?php } ?>


                                        <?php
            if($ext == "us"){ ?>
                                        <div class="form-group">
                                            <label class="label-control">Nexus Category</label>
                                            <select name="Nexus Category" class="form-control">
                                                <option>C11</option>
                                                <option>C12</option>
                                                <option>C21</option>
                                                <option>C31</option>
                                                <option>C32</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Nexus Country</label>
                                            <input name="Nexus Country" type="text" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Application Purpose</label>
                                            <select name="Application Purpose" class="form-control">
                                                <option>Business use for profit</option>
                                                <option>Non-profit business</option>
                                                <option>Club</option>
                                                <option>Association</option>
                                                <option>Religious Organization</option>
                                                <option>Personal Use</option>
                                                <option>Educational purposes</option>
                                                <option>Government purposes</option>
                                            </select>
                                        </div>

                                        <?php }  


            // .CO.UK
            if($ext == "co.uk" || $ext == "net.uk" || $ext == "org.uk" || $ext == "plc.uk" || $ext == "ltd.uk" || $ext == "me.uk" || $ext == "uk"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Legal Type</label>
                                            <select name="Legal Type" class="form-control">
                                                <option>Individual</option>
                                                <option>UK Limited Company</option>
                                                <option>UK Public Limited Company</option>
                                                <option>UK Partnership</option>
                                                <option>UK Limited Liability Partnership</option>
                                                <option>Sole Trader</option>
                                                <option>UK Registered Charity</option>
                                                <option>UK Entity (other)</option>
                                                <option>Foreign Organization</option>
                                                <option>Other foreign organizations</option>
                                                <option>UK Industrial/Provident Registered Company</option>
                                                <option>UK School</option>
                                                <option>UK Government Body</option>
                                                <option>UK Corporation by Royal Charter</option>
                                                <option>UK Statutory Body</option>
                                                <option>Non-UK Individual</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Nexus Country</label>
                                            <input name="Nexus Country" type="text" class="form-control" required>
                                        </div>



                                        <div class="form-group">
                                            <label class="label-control">Application Purpose</label>
                                            <select name="Application Purpose" class="form-control">
                                                <option>Business use for profit</option>
                                                <option>Non-profit business</option>
                                                <option>Club</option>
                                                <option>Association</option>
                                                <option>Religious Organization</option>
                                                <option>Personal Use</option>
                                                <option>Educational purposes</option>
                                                <option>Government purposes</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Company ID Number</label>
                                            <input name="Company ID Number" type="text" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Registrant Name</label>
                                            <input name="Registrant Name" type="text" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">WHOIS Opt-out</label>
                                            <input type="checkbox" name="WHOIS Opt-out">
                                        </div>

                                        <?php } 
            
            

            // .CA
            if($ext == "ca"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Legal Type</label>
                                            <select name="Legal Type" class="form-control">
                                                <option>Corporation</option>
                                                <option>Canadian Citizen</option>
                                                <option>Permanent Resident of Canada</option>
                                                <option>Government</option>
                                                <option>Canadian Educational Institution</option>
                                                <option>Canadian Unincorporated Association</option>
                                                <option>Canadian Hospital</option>
                                                <option>Partnership Registered in Canada</option>
                                                <option>Trademark registered in Canada</option>
                                                <option>Canadian Trade Union</option>
                                                <option>Canadian Political Party</option>
                                                <option>Canadian Library Archive or Museum</option>
                                                <option>Trust established in Canada</option>
                                                <option>Aboriginal Peoples</option>
                                                <option>Legal Representative of a Canadian</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">CIRA Agreement</label>
                                            <input name="CIRA Agreement" type="checkbox"> <br>
                                            Tick to confirm you agree to the CIRA Registration Agreement shown
                                            below<br />
                                            <blockquote>You have read, understood and agree to the terms and conditions
                                                of the Registrant Agreement, and that CIRA may, from time to time and at
                                                its discretion, amend any or all of the terms and conditions of the
                                                Registrant Agreement, as CIRA deems appropriate, by posting a notice of
                                                the changes on the CIRA website and by sending a notice of any material
                                                changes to Registrant. You meet all the requirements of the Registrant
                                                Agreement to be a Registrant, to apply for the registration of a Domain
                                                Name Registration, and to hold and maintain a Domain Name Registration,
                                                including without limitation CIRA's Canadian Presence Requirements for
                                                Registrants, at: www.cira.ca/assets/Documents/Legal/Registrants/CPR.pdf.
                                                CIRA will collect, use and disclose your personal information, as set
                                                out in CIRA's Privacy Policy, at:
                                                www.cira.ca/assets/Documents/Legal/Registrants/privacy.pdf</blockquote>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">WHOIS Opt-out</label>
                                            <input type="checkbox" name="WHOIS Opt-out">
                                        </div>

                                        <?php } 
            

            // .ES
            if($ext == "es"){ ?>


                                        <div class="form-group">
                                            <label class="label-control">ID Form Type</label>
                                            <select name="ID Form Type" class="form-control">
                                                <option>Other Identification</option>
                                                <option>Tax Identification Number</option>
                                                <option>Tax Identification Code</option>
                                                <option>Foreigner Identification Number</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">ID Form Number</label>
                                            <input name="ID Form Number" class="form-control" type="text" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Legal Form</label>
                                            <select name="Legal Form" class="form-control">
                                                <option value='1'>Individual</option>
                                                <option value='39'>Economic Interest Grouping</option>
                                                <option value='47'>Association</option>
                                                <option value='59'>Sports Association</option>
                                                <option value='68'>Professional Association</option>
                                                <option value='124'>Savings Bank</option>
                                                <option value='150'>Community Property</option>
                                                <option value='152'>Community of Owners</option>
                                                <option value='164'>Order or Religious Institution</option>
                                                <option value='181'>Consulate</option>
                                                <option value='197'>Public Law Association</option>
                                                <option value='203'>Embassy</option>
                                                <option value='229'>Local Authority</option>
                                                <option value='269'>Sports Federation</option>
                                                <option value='286'>Foundation</option>
                                                <option value='365'>Mutual Insurance Company</option>
                                                <option value='434'>Regional Government Body</option>
                                                <option value='436'>Central Government Body</option>
                                                <option value='439'>Political Party</option>
                                                <option value='476'>Trade Union</option>
                                                <option value='524'>Public Limited Company</option>
                                                <option value='554'>Civil Society</option>
                                                <option value='560'>General Partnership</option>
                                                <option value='562'>General and Limited Partnership</option>
                                                <option value='566'>Cooperative</option>
                                                <option value='608'>Worker-owned Company</option>
                                                <option value='612'>Limited Company</option>
                                                <option value='713'>Spanish Office</option>
                                                <option value='717'>Temporary Alliance of Enterprises</option>
                                                <option value='744'>Worker-owned Limited Company</option>
                                                <option value='745'>Regional Public Entity</option>
                                                <option value='746'>National Public Entity</option>
                                                <option value='747'>Local Public Entity</option>
                                                <option value='877'>Others</option>
                                                <option value='878'>Designation of Origin Supervisory Council</option>
                                                <option value='879'>Entity Managing Natural Areas</option>
                                            </select>
                                        </div>

                                        <? } 

            // .SG
            if($ext == "sg" || $ext == "com.sg" ||$ext == "edu.sg" ||$ext == "net.sg" ||$ext == "org.sg" ||$ext == "per.sg"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Registrant Type</label>
                                            <select name="Registrant Type" class="form-control">
                                                <option>Individual</option>
                                                <option>Organisation</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">RCB Singapore ID</label>
                                            <input name="RCB Singapore ID" type="text" class="form-control" required>
                                        </div>

                                        <?php }

            // .TEL
            if($ext == "tel"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Legal Type</label>
                                            <select name="Legal Type" class="form-control">
                                                <option>Natural Person</option>
                                                <option>Legal Person</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">WHOIS Opt-out</label>
                                            <input type="checkbox" name="WHOIS Opt-out">
                                        </div>

                                        <?php }


            // .IT
            if($ext == "it"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Legal Type</label>
                                            <select name="Legal Type" class="form-control">
                                                <option>Italian and foreign natural persons</option>
                                                <option>Companies/one man companies</option>
                                                <option>Freelance workers/professionals</option>
                                                <option>Non-profit organizations</option>
                                                <option>Public organizations</option>
                                                <option>Other subjects</option>
                                                <option>Non natural foreigners</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Tax ID</label>
                                            <input type="text" name="Tax ID" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Publish Personal Data</label>
                                            <input type="checkbox" name="Publish Personal Data">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Accept Section 3 of .IT registrar
                                                contract</label>
                                            <input type="checkbox" name="Accept Section 3 of .IT registrar contract">
                                            <br>
                                            <label class="label-control">Accept Section 5 of .IT registrar
                                                contract</label>
                                            <input type="checkbox" name="Accept Section 5 of .IT registrar contract">
                                            <br>
                                            <label class="label-control">Accept Section 6 of .IT registrar
                                                contract</label>
                                            <input type="checkbox" name="Accept Section 6 of .IT registrar contract">
                                            <br>
                                            <label class="label-control">Accept Section 7 of .IT registrar
                                                contract</label>
                                            <input type="checkbox" name="Accept Section 7 of .IT registrar contract">
                                        </div>

                                        <?php }


            // .DE
            if($ext == "de"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Tax ID</label>
                                            <input type="text" name="Tax ID" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Address Confirmation</label>
                                            <input type="checkbox" name="Address Confirmation"><br>
                                            Please tick to confirm you have a valid German address
                                        </div>

                                        <?php }


            // .AU
            if($ext == "com.au" || $ext == "net.au" || $ext == "org.au" || $ext == "asn.au" || $ext == "id.au"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Registrant Name</label>
                                            <input type="text" name="Registrant Name" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Registrant ID</label>
                                            <input type="text" name="Registrant ID" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Registrant ID Type</label>
                                            <select name="Registrant ID Type" class="form-control">
                                                <option>ABN</option>
                                                <option>ACN</option>
                                                <option>Business Registration Number</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Eligibility Name</label>
                                            <input type="text" name="Eligibility Name" class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Eligibility ID</label>
                                            <input type="text" name="Eligibility ID" class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Eligibility ID Type</label>
                                            <select name="Eligibility ID Type" class="form-control">
                                                <option>Australian Company Number (ACN)</option>
                                                <option>ACT Business Number</option>
                                                <option>NSW Business Number</option>
                                                <option>NT Business Number</option>
                                                <option>QLD Business Number</option>
                                                <option>SA Business Number</option>
                                                <option>TAS Business Number</option>
                                                <option>VIC Business Number</option>
                                                <option>WA Business Number</option>
                                                <option>Trademark (TM)</option>
                                                <option>Other</option>
                                                <option>Australian Business Number (ABN)</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Eligibility Type</label>
                                            <select name="Eligibility Type" class="form-control">
                                                <option>Charity</option>
                                                <option>Citizen/Resident</option>
                                                <option>Club</option>
                                                <option>Commercial Statutory Body</option>
                                                <option>Company</option>
                                                <option>Incorporated Association</option>
                                                <option>Industry Body</option>
                                                <option>Non-profit Organisation</option>
                                                <option>Other</option>
                                                <option>Partnership</option>
                                                <option>Pending TM Owner</option>
                                                <option>Political Party</option>
                                                <option>Registered Business</option>
                                                <option>Religious/Church Group</option>
                                                <option>Sole Trader</option>
                                                <option>Trade Union</option>
                                                <option>Trademark</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Eligibility Reason</label>
                                            <select name="Eligibility Reason" class="form-control">
                                                <option>Domain name is an Exact Match Abbreviation or Acronym of your
                                                    Entity or Trading Name</option>
                                                <option>Close and substantial connection between the domain name and the
                                                    operations of your Entity</option>
                                            </select>
                                        </div>

                                        <?php }


            // .ASIA
            if($ext == "asia"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Eligibility Reason</label>
                                            <select name="Eligibility Reason" class="form-control">
                                                <option>Domain name is an Exact Match Abbreviation or Acronym of your
                                                    Entity or Trading Name</option>
                                                <option>Close and substantial connection between the domain name and the
                                                    operations of your Entity</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Legal Type</label>
                                            <select name="Legal Type" class="form-control">
                                                <option>naturalPerson</option>
                                                <option>corporation</option>
                                                <option>cooperative</option>
                                                <option>partnership</option>
                                                <option>government</option>
                                                <option>politicalParty</option>
                                                <option>society</option>
                                                <option>institution</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Identity Form</label>
                                            <select name="Identity Form" class="form-control">
                                                <option>passport</option>
                                                <option>certificate</option>
                                                <option>legislation</option>
                                                <option>societyRegistry</option>
                                                <option>politicalPartyRegistry</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Identity Number</label>
                                            <input type="text" name="Identity Number" class="form-control" required>
                                        </div>

                                        <?php }


            // .PRO
            if($ext == "pro"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Indicated professional association recognized
                                                by government body</label>
                                            <input type="text" name="Profession" class="form-control" required>

                                            <label class="label-control">The license number of the registrant's
                                                credentials, if applicable</label>
                                            <input type="text" name="License Number" class="form-control" required>

                                            <label class="label-control">The name of the authority from which the
                                                registrant receives their professional credentials</label>
                                            <input type="text" name="Authority" class="form-control" required>

                                            <label class="label-control">The URL to an online resource for the
                                                authority, preferably, a member search directory</label>
                                            <input type="text" name="Authority Website" class="form-control" required>
                                        </div>

                                        <?php }


            // .COOP
            if($ext == "coop"){ ?>


                                        <div class="form-group">
                                            <label class="label-control">Contact Name</label>
                                            <input type="text" name="Contact Name" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Contact Company</label>
                                            <input type="text" name="Contact Company" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Contact Email</label>
                                            <input type="text" name="Contact Email" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Address 1</label>
                                            <input type="text" name="Address 1" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Address 2</label>
                                            <input type="text" name="Address 2" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">City</label>
                                            <input type="text" name="City" class="form-control" required>
                                        </div>



                                        <div class="form-group">
                                            <label class="label-control">State</label>
                                            <input type="text" name="State" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">ZIP Code</label>
                                            <input type="text" name="ZIP Code" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Country</label>
                                            <input type="text" name="Country" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Phone CC</label>
                                            <input type="text" name="Phone CC" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Phone</label>
                                            <input type="text" name="Phone" class="form-control" required>
                                        </div>

                                        <?php }


            // .CN
            if($ext == "cn"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Hosted in China?</label>
                                            <input name="cnhosting" type="checkbox">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control"> Agree to the .CN <a
                                                    href="http://www1.cnnic.cn/PublicS/fwzxxgzcfg/201208/t20120830_35735.htm\"
                                                    target="_blank">Register Agreement</a>
                                            </label>
                                            <input name="cnhregisterclause" type="checkbox">
                                        </div>

                                        <?php }


            // .FR
            if($ext == "fr" || $ext == "re" || $ext == "pm" || $ext == "tf" || $ext == "wf" || $ext == "yt"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Legal Type</label>
                                            <select name="Legal Type" class="form-control">
                                                <option>Individual</option>
                                                <option>Company</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Birthdate</label>
                                            <input name="Birthdate" type="text" class="form-control"
                                                placeholder="1900-01-01">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Birthplace City</label>
                                            <input name="Birthplace City" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Birthplace Country</label>
                                            <input name="Birthplace Country" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Birthplace Postcode</label>
                                            <input name="Birthplace Postcode" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">SIRET Number</label>
                                            <input name="SIRET Number" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">DUNS Number</label>
                                            <input name="DUNS Number" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">VAT Number</label>
                                            <input name="VAT Number" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Trademark Number</label>
                                            <input name="Trademark Number" type="text" class="form-control">
                                        </div>


                                        <?php }


            // .NU
            if($ext == "nu"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Identification Number</label>
                                            <input name="Identification Number" type="text" class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">VAT Number</label>
                                            <input name="VAT Number" type="text" class="form-control">
                                        </div>

                                        <?php }


            // .QUEBEC
            if($ext == "quebec"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Intended Use</label>
                                            <input type="text" name="Intended Use" class="form-control" required>
                                        </div>

                                        <?php }


            // .JOBS
            if($ext == "jobs"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Website</label>
                                            <input name="Website" type="text" class="form-control">
                                        </div>

                                        <?php }


            // .TRAVEL
            if($ext == "travel"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Trustee Service</label>
                                            <select name="Trustee Service" class="form-control">
                                                <option value="TRUST">Use Trustee</option>
                                                <option value="UIN">Use My Information (Requires UIN)</option>
                                            </select>
                                            Trustee service allows you to register domains under the name of the trustee
                                            if you do not meet the requiremets.
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">TRAVEL UIN Code</label>
                                            <input name="TRAVEL UIN Code" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Trustee Service Agreement</label>
                                            <input type="checkbox" name="Trustee Service Agreement"> <br>
                                            I agree to the <a href="http://www.101domain.com/trustee_agreement.htm"
                                                target="_BLANK">Trustee Service Agreement</a>
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">TRAVEL Usage Agreement</label>
                                            <input type="checkbox" name="TRAVEL Usage Agreement"><br>
                                            I agree that .travel domains are restricted to those who are primarily
                                            active in the travel industry
                                        </div>

                                        <?php }


            // .RU
            if($ext == "ru" || $ext == "xn--p1ai"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Registrant Type</label>
                                            <select name="Registrant Type" class="form-control">
                                                <option value="ORG">Organization</option>
                                                <option value="IND">Individual</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Individuals: Birthday</label>
                                            <input type="text" name="Individuals Birthday" class="form-control"
                                                placeholder="YYYY-MM-DD">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Individuals: Passport Number</label>
                                            <input type="text" name="Individuals Passport Number" class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Individuals: Passport Issuer</label>
                                            <input type="text" name="Individuals Passport Issuer" class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Individuals: Passport Issue Date</label>
                                            <input type="text" name="Individuals Passport Issue Date"
                                                class="form-control" placeholder="YYYY-MM-DD">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Individuals: Whois Privacy</label>
                                            <select name="Individuals: Whois Privacy" class="form-control">
                                                <option>No</option>
                                                <option>Yes</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Russian Organizations: Taxpayer Number
                                                (ИНН)</label>
                                            <input type="text" name="Russian Organizations Taxpayer Number 1"
                                                class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Russian Organizations: Territory-Linked
                                                Taxpayer Number (КПП)</label>
                                            <input type="text"
                                                name="Russian Organizations Territory-Linked Taxpayer Number 2"
                                                class="form-control">
                                        </div>

                                        <?php }


            // .RO
            if($ext == "ro" || $ext == "srts.ro" || $ext == "co.ro"|| $ext == "com.ro"|| $ext == "firm.ro"|| $ext == "info.ro"|| $ext == "nom.ro"|| 
            $ext == "nt.ro"|| $ext == "org.ro"|| $ext == "rec.ro"|| $ext == "ro.ro"|| $ext == "store.ro"|| $ext == "tm.ro"|| $ext == "www.ro"){ ?>


                                        <div class="form-group">
                                            <label class="label-control">Registrant Type</label>
                                            <select name="Registrant Type" class="form-control">
                                                <option value="p">Private Person</option>
                                                <option value="ap">Authorized Person</option>
                                                <option value="nc">Non-Commercial Organization</option>
                                                <option value="c">Commercial</option>
                                                <option value="gi">Government Institute</option>
                                                <option value="pi">Public Institute</option>
                                                <option value="o">Other Juridicial</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">CNPFiscalCode</label>
                                            <input type="text" name="CNPFiscalCode" class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Registration Number</label>
                                            <input type="text" name="Registration Number" class="form-control">
                                        </div>

                                        <?php }


            // .HK
            if($ext == "hk" || $ext == "com.hk" || $ext == "edu.hk"|| $ext == "gov.hk"|| $ext == "idv.hk"|| $ext == "net.hk"|| $ext == "org.hk" ){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Organizations Industry Type</label>
                                            <select name="Organizations Industry Type" class="form-control">
                                                <option value="010100">Plastics / Petro-Chemicals / Chemicals - Plastics
                                                    &amp; Plastic Products</option>
                                                <option value="010200">Plastics / Petro-Chemicals / Chemicals - Rubber
                                                    &amp; Rubber Products</option>
                                                <option value="010300">Plastics / Petro-Chemicals / Chemicals - Fibre
                                                    Materials &amp; Products</option>
                                                <option value="010400">Plastics / Petro-Chemicals / Chemicals -
                                                    Petroleum / Coal &amp; Other Fuels</option>
                                                <option value="010500">Plastics / Petro-Chemicals / Chemicals -
                                                    Chemicals &amp; Chemical Products</option>
                                                <option value="020100">Metals / Machinery / Equipment - Metal Materials
                                                    &amp; Treatment</option>
                                                <option value="020200">Metals / Machinery / Equipment - Metal Products
                                                </option>
                                                <option value="020300">Metals / Machinery / Equipment - Industrial
                                                    Machinery &amp; Supplies</option>
                                                <option value="020400">Metals / Machinery / Equipment - Precision &amp;
                                                    Optical Equipment</option>
                                                <option value="020500">Metals / Machinery / Equipment - Moulds &amp;
                                                    Dies</option>
                                                <option value="030100">Printing / Paper / Publishing - Printing /
                                                    Photocopying / Publishing</option>
                                                <option value="030200">Printing / Paper / Publishing - Paper / Paper
                                                    Products</option>
                                                <option value="040100">Construction / Decoration / Environmental
                                                    Engineering - Construction Contractors</option>
                                                <option value="040200">Construction / Decoration / Environmental
                                                    Engineering - Construction Materials</option>
                                                <option value="040300">Construction / Decoration / Environmental
                                                    Engineering - Decoration Materials</option>
                                                <option value="040400">Construction / Decoration / Environmental
                                                    Engineering - Construction / Safety Equipment &amp; Supplies
                                                </option>
                                                <option value="040500">Construction / Decoration / Environmental
                                                    Engineering - Decoration / Locksmiths / Plumbing &amp; Electrical
                                                    Works</option>
                                                <option value="040600">Construction / Decoration / Environmental
                                                    Engineering - Fire Protection Equipment &amp; Services</option>
                                                <option value="040700">Construction / Decoration / Environmental
                                                    Engineering - Environmental Engineering / Waste Reduction</option>
                                                <option value="050100">Textiles / Clothing &amp; Accessories - Textiles
                                                    / Fabric</option>
                                                <option value="050200">Textiles / Clothing &amp; Accessories - Clothing
                                                </option>
                                                <option value="050300">Textiles / Clothing &amp; Accessories - Uniforms
                                                    / Special Clothing</option>
                                                <option value="050400">Textiles / Clothing &amp; Accessories - Clothing
                                                    Manufacturing Accessories</option>
                                                <option value="050500">Textiles / Clothing &amp; Accessories - Clothing
                                                    Processing &amp; Equipment</option>
                                                <option value="050600">Textiles / Clothing &amp; Accessories - Fur /
                                                    Leather &amp; Leather Goods</option>
                                                <option value="050700">Textiles / Clothing &amp; Accessories - Handbags
                                                    / Footwear / Optical Goods / Personal Accessories</option>
                                                <option value="060100">Electronics / Electrical Appliances - Electronic
                                                    Equipment &amp; Supplies</option>
                                                <option value="060200">Electronics / Electrical Appliances - Electronic
                                                    Parts &amp; Components</option>
                                                <option value="060300">Electronics / Electrical Appliances - Electrical
                                                    Appliances / Audio-Visual Equipment</option>
                                                <option value="070100">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Kitchenware / Tableware</option>
                                                <option value="070200">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Bedding</option>
                                                <option value="070300">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Bathroom / Cleaning Accessories</option>
                                                <option value="070400">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Household Goods</option>
                                                <option value="070500">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Wooden / Bamboo &amp; Rattan Goods</option>
                                                <option value="070600">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Home Furnishings / Arts &amp; Crafts</option>
                                                <option value="070700">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Watches / Clocks</option>
                                                <option value="070800">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Jewellery Accessories</option>
                                                <option value="070900">Houseware / Watches / Clocks / Jewellery / Toys /
                                                    Gifts - Toys / Games / Gifts</option>
                                                <option value="080100">Business &amp; Professional Services / Finance -
                                                    Accounting / Legal Services</option>
                                                <option value="080200">Business &amp; Professional Services / Finance -
                                                    Advertising / Promotion Services</option>
                                                <option value="080300">Business &amp; Professional Services / Finance -
                                                    Consultancy Services</option>
                                                <option value="080400">Business &amp; Professional Services / Finance -
                                                    Translation / Design Services</option>
                                                <option value="080500">Business &amp; Professional Services / Finance -
                                                    Cleaning / Pest Control Services</option>
                                                <option value="080600">Business &amp; Professional Services / Finance -
                                                    Security Services</option>
                                                <option value="080700">Business &amp; Professional Services / Finance -
                                                    Trading / Business Services</option>
                                                <option value="080800">Business &amp; Professional Services / Finance -
                                                    Employment Services</option>
                                                <option value="080900">Business &amp; Professional Services / Finance -
                                                    Banking / Finance / Investment</option>
                                                <option value="081000">Business &amp; Professional Services / Finance -
                                                    Insurance</option>
                                                <option value="081100">Business &amp; Professional Services / Finance -
                                                    Property / Real Estate</option>
                                                <option value="090100">Transportation / Logistics - Land Transport /
                                                    Motorcars</option>
                                                <option value="090200">Transportation / Logistics - Sea Transport /
                                                    Boats</option>
                                                <option value="090400">Transportation / Logistics - Moving / Warehousing
                                                    / Courier &amp; Logistics Services</option>
                                                <option value="090500">Transportation / Logistics - Freight Forwarding
                                                </option>
                                                <option value="100100">Office Equipment / Furniture / Stationery /
                                                    Information Technology - Office / Commercial Equipment &amp;
                                                    Supplies</option>
                                                <option value="100200">Office Equipment / Furniture / Stationery /
                                                    Information Technology - Office &amp; Home Furniture</option>
                                                <option value="100300">Office Equipment / Furniture / Stationery /
                                                    Information Technology - Stationery &amp; Educational Supplies
                                                </option>
                                                <option value="100400">Office Equipment / Furniture / Stationery /
                                                    Information Technology - Telecommunication Equipment &amp; Services
                                                </option>
                                                <option value="100500">Office Equipment / Furniture / Stationery /
                                                    Information Technology - Computers / Information Technology</option>
                                                <option value="110100">Food / Flowers / Fishing &amp; Agriculture - Food
                                                    Products &amp; Supplies</option>
                                                <option value="110200">Food / Flowers / Fishing &amp; Agriculture -
                                                    Beverages / Tobacco</option>
                                                <option value="110300">Food / Flowers / Fishing &amp; Agriculture -
                                                    Restaurant Equipment &amp; Supplies</option>
                                                <option value="110400">Food / Flowers / Fishing &amp; Agriculture -
                                                    Flowers / Artificial Flowers / Plants</option>
                                                <option value="110500">Food / Flowers / Fishing &amp; Agriculture -
                                                    Fishing</option>
                                                <option value="110600">Food / Flowers / Fishing &amp; Agriculture -
                                                    Agriculture</option>
                                                <option value="120100">Medical Services / Beauty / Social Services -
                                                    Medicine &amp; Herbal Products</option>
                                                <option value="120200">Medical Services / Beauty / Social Services -
                                                    Medical &amp; Therapeutic Services</option>
                                                <option value="120300">Medical Services / Beauty / Social Services -
                                                    Medical Equipment &amp; Supplies</option>
                                                <option value="120400">Medical Services / Beauty / Social Services -
                                                    Beauty / Health</option>
                                                <option value="120500">Medical Services / Beauty / Social Services -
                                                    Personal Services</option>
                                                <option value="120600">Medical Services / Beauty / Social Services -
                                                    Organizations / Associations</option>
                                                <option value="120700">Medical Services / Beauty / Social Services -
                                                    Information / Media</option>
                                                <option value="120800">Medical Services / Beauty / Social Services -
                                                    Public Utilities</option>
                                                <option value="120900">Medical Services / Beauty / Social Services -
                                                    Religion / Astrology / Funeral Services</option>
                                                <option value="130100">Culture / Education - Music / Arts</option>
                                                <option value="130200">Culture / Education - Learning Instruction &amp;
                                                    Training</option>
                                                <option value="130300">Culture / Education - Elementary Education
                                                </option>
                                                <option value="130400">Culture / Education - Tertiary Education / Other
                                                    Education Services</option>
                                                <option value="130500">Culture / Education - Sporting Goods</option>
                                                <option value="130600">Culture / Education - Sporting / Recreational
                                                    Facilities &amp; Venues</option>
                                                <option value="130700">Culture / Education - Hobbies / Recreational
                                                    Activities</option>
                                                <option value="130800">Culture / Education - Pets / Pets Services &amp;
                                                    Supplies</option>
                                                <option value="140101">Dining / Entertainment / Shopping / Travel -
                                                    Restaurant Guide - Chinese</option>
                                                <option value="140102">Dining / Entertainment / Shopping / Travel -
                                                    Restaurant Guide - Asian</option>
                                                <option value="140103">Dining / Entertainment / Shopping / Travel -
                                                    Restaurant Guide - Western</option>
                                                <option value="140200">Dining / Entertainment / Shopping / Travel -
                                                    Catering Services / Eateries</option>
                                                <option value="140300">Dining / Entertainment / Shopping / Travel -
                                                    Entertainment Venues</option>
                                                <option value="140400">Dining / Entertainment / Shopping / Travel -
                                                    Entertainment Production &amp; Services</option>
                                                <option value="140500">Dining / Entertainment / Shopping / Travel -
                                                    Entertainment Equipment &amp; Facilities</option>
                                                <option value="140600">Dining / Entertainment / Shopping / Travel -
                                                    Shopping Venues</option>
                                                <option value="140700">Dining / Entertainment / Shopping / Travel -
                                                    Travel / Hotels &amp; Accommodation</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Organizations Supporting Documentation</label>
                                            <select name="Organizations Supporting Documentation" class="form-control">
                                                <option value="BR">Business Registration Certificate</option>
                                                <option value="CI">Certificate of Incorporation</option>
                                                <option value="CRS">Certificate of Registration of a School</option>
                                                <option value="HKSARG">Hong Kong Special Administrative Region Gov\'t
                                                    Dept</option>
                                                <option value="HKORDINANCE">Ordinance of Hong Kong</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Individuals Supporting Documentation</label>
                                            <select name="Individuals Supporting Documentation" class="form-control">
                                                <option value="HKID">Hong Kong Identity Number</option>
                                                <option value="OTHID">Other Country Identity Number</option>
                                                <option value="PASSNO">Passport No</option>
                                                <option value="BIRTHCERT">Birth Certificate</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">Registrant Type</label>
                                            <select name="Registrant Type" class="form-control">
                                                <option value="ind">Individual</option>
                                                <option value="org">Organization</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Organizations: Name in Chinese</label>
                                            <input type="text" name="Organizations Name in Chinese"
                                                class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Organizations: Document Number</label>
                                            <input type="text" name="Organizations Document Number"
                                                class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Organizations: Issuing Country</label>
                                            <input type="text" name="Organizations Issuing Country"
                                                class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Individuals: Document Number</label>
                                            <input type="text" name="Individuals Document Number" class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">Individuals: Issuing Country</label>
                                            <input type="text" name="Individuals Issuing Country" class="form-control">
                                        </div>



                                        <div class="form-group">
                                            <label class="label-control">Individuals: Under 18 years Old?</label>
                                            <select name="Individuals Under 18" class="form-control">
                                                <option>No</option>
                                                <option>Yes</option>
                                            </select>
                                        </div>



                                        <?php }


            // .AERO
            if($ext == "aero"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">AERO ID</label>
                                            <input type="text" name="AERO ID" class="form-control" required>
                                        </div>


                                        <div class="form-group">
                                            <label class="label-control">AERO Key</label>
                                            <input type="text" name="AERO Key" class="form-control">
                                        </div>

                                        <?php }

            // .PL
            if($ext == "pl" || $ext == "pc.pl" || $ext == "miasta.pl"|| $ext == "atm.pl"|| $ext == "rel.pl"|| $ext == "gmina.pl"|| $ext == "szkola"|| 
            $ext == "sos.pl"|| $ext == "media.pl"|| $ext == "edu.pl"|| $ext == "auto.pl"|| $ext == "agro.pl"|| $ext == "turystyka.pl"|| $ext == "gov.pl" ||
            $ext == "aid.pl"|| $ext == "nieruchomosci.pl"|| $ext == "com.pl"|| $ext == "priv.pl"|| $ext == "tm.pl"|| $ext == "travel.pl"|| $ext == "info.pl" ||
            $ext == "org.pl"|| $ext == "net.pl"|| $ext == "sex.pl"|| $ext == "sklep.pl"|| $ext == "powiat.pl"|| $ext == "mail.pl"|| $ext == "realestate.pl" ||
            $ext == "shop.pl"|| $ext == "mil.pl"|| $ext == "nom.pl"|| $ext == "gsm.pl"|| $ext == "tourism.pl"|| $ext == "targi.pl"|| $ext == "biz.pl" ){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Publish Contact in .PL WHOIS</label>
                                            <select name="Publish Contact in .PL WHOIS" class="form-control">
                                                <option>yes</option>
                                                <option>no</option>
                                            </select>
                                        </div>


                                        <?php }

            // .SE
            if($ext == "se" || $ext == "tm.se" || $ext == "org.se" || $ext == "pp.se" || $ext == "parti.se" || $ext == "presse.se"){ ?>

                                        <div class="form-group">
                                            <label class="label-control">Identification Number</label>
                                            <input type="text" name="Identification Number" class="form-control">
                                            For Sweedish Residents: Personal or Organization Number; For residents of
                                            other countries: Civic Registration Number, Company Registration Number or
                                            Passport Number
                                        </div>

                                        <div class="form-group">
                                            <label class="label-control">VAT</label>
                                            <input type="text" name="VAT" class="form-control">
                                            Required for EU companies not located in Sweeden
                                        </div>

                                        <?php } ?>

                                        <input type="submit" class="btn btn-success pull-right"
                                            value="<?=lang('submit')?>">
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </div>
</section>