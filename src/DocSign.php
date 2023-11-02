<?php
namespace Simcify;
use Simcify\Models\StudentModel;

class DocSign {
    public static function sendAgreement($email, $user_id, $lease_on_file=0) {  //send document sign
        header('Content-type: application/json');
        $user = Database::table("users")->find($user_id);
        $doc_temp = Database::table("files")->where("document_key", env("Template_Key"))->first();

        $documentKey= $user_id."_".Str::random(10);
        $data = array(
            "name" => $doc_temp->name." (".$email.")",
            "filename" => $doc_temp->filename,
            "size" => $doc_temp->size,
            "template_fields" => $doc_temp->template_fields,
            "document_key" => $documentKey,
            'company' => $user->company
        );
        Database::table("files")->insert($data);

        $template_fields = json_decode($doc_temp->template_fields, true);
        $savedWidth = array_shift($template_fields);  //Remove the first element (red) from an array, and return the value of the removed element:
        if (empty($savedWidth)) { $savedWidth = 0; }

        $document = Database::table("files")->where("document_key", $documentKey)->first();
        $document->outputName=$user_id."_".Str::random(3).".pdf";
        $templateFields=PdfBuilder::Build($document,$template_fields,$savedWidth,$user);

        $duplicateActivity = 'Signing request sent to <span class="text-primary">'.escape($email).'</span> by <span class="text-primary">Hiawatha</span>.';
        Signer::keephistory($documentKey, $duplicateActivity, "default");

        $signingKey = Str::random(32);
        $signingLink = env("APP_URL")."/view/".$signingKey."?cmd=".Str::random(32);
        $trackerLink = env("APP_URL")."/mailopen?signingKey=".$signingKey;

        $request = array(
            "company" => $user->company,
            "document" => $documentKey,
            "signing_key" => $signingKey,
            "positions" => json_encode($templateFields, JSON_UNESCAPED_UNICODE),
            "email" => $email,
            "sender" => Auth::user()->id,
            "receiver" => $user_id );

        if($lease_on_file == 1){
            $request['update_time']= date("Y-m-d H:i:s");
            $request['status']= "Signed";
        }
        else{
            StudentModel::Update($user_id,array('sign_status'=>StudentModel::Sent));
            if (env('SITE_Portal')){
                $message="Hello,
<br><br>
On behalf of all of us at International Residence Hall, we certainly look forward to welcoming you to our new Home.  We hope your experience with us is second to none, and will strive to provide a comfortable, safe, relaxing environment during your time with us.  Please see below a link to our registration software.  This online portal will allow you access to your account anywhere at any time.  The more information you can fill in prior to your arrival, the easier the check in process will be.
<br><br>
In order to complete the registration process, you will need a copy of your passport, a headshot photo for your ID, and payment type.  Within the registration, you will need to sign a contract and handbook, as well as pay an initial deposit.  Should you have any questions prior to arrival please reach out.  We can be contacted at pf@irhliving.com or by calling 877-808-4474.
<br><br>
Safe Travels, we look forward to serving you soon.
<br><br>
All the Best,<br>
Scott Bell<br>
General Manager<br> 
International Residence Hall<br>
Pigeon Forge, TN<br>
(585) 899-0127";
            }
            else{
                $message="You have been invited by Hiawatha inc.<br> Click the link above to create your account and Sign.<br><br>Thank you<br>".env("APP_NAME")." Team";
            }
            $message.="<img src='".$trackerLink."' width='0' height='0'>";

            Mail::send(
                $email, env("Site_Name")." Create Account",
                array(
                    "title" => "Create Account",
                    "subtitle" => "Click the link below to create your account.",
                    "buttonText" => "Create Account",
                    "buttonLink" => $signingLink,
                    "message" => $message
                ),
                "withbutton"
            );
        }

        Database::table("requests")->insert($request);
        return "OK";
    }

}

