<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use function PHPUnit\Framework\countOf;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CampaignResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateCampaignRequest;

use App\Http\Requests\UpdateCampaignRequest;
use App\Models\CampaignImage;
use Filament\Forms\Get;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;

class CampaignController extends Controller
{
    //
	// CreateCampaign(writer http.ResponseWriter, request *http.Request)
    public function createCampaign(CreateCampaignRequest $request) :CampaignResource {
        $curent_user = $request->get("current_user");
        if (!$curent_user){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                        ]
                        ]
                    ],400));
                }
            // abort(400, "damn error");
        // }
                
                // dd("momo");
        $data = $request->validated();
        // dd($data);
        $campi = new Campaign($data);
        $campi->setSlug($curent_user->id);
        $campi->user_id = $curent_user->id;
        // dd($campi);
        $campi->save();
        
        return new CampaignResource($campi);
        // return response()->json([
        //     "message"=>"koko"
        // ]);
    }


	// UpdateCampaign(writer http.ResponseWriter, request *http.Request)


    public function updateCampaign(int $cid, UpdateCampaignRequest $request):CampaignResource 
    {
        $curent_user = $request->get("current_user");
        if (!$curent_user){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                        ]
                        ]
                    ],400));
                }

        $data = $request->validated();

        // dd($data);
        $campi = Campaign::query()->where("id",$cid)->where("user_id",$curent_user->id)->first();
        if (!$campi){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "data not found"
                        ]
                        ]
                    ],400));
                }
        $campi->fill($data);
        // dd($data, $cid, $campi);
        $campi->setSlug($curent_user->id);
        $campi->user_id = $curent_user->id;
        $campi->save();
        return new CampaignResource($campi);
    }


    	// GetCampaigns(writer http.ResponseWriter, request *http.Request)

        /**
         * @unauthenticated
        */
        public function getCampaigns(Request $request):JsonResponse{

            $data = Campaign::query()->get();
            // dd( empty($data), sizeof($data) == 0, count($data) == 0 );
            if (count($data) == 0 ) {
                throw new HttpResponseException(response([
                    "errors"=> [
                        "message"=>[
                            "data not found"
                            ]
                            ]
                        ],400));
            };


            return CampaignResource::collection($data)->response();
        }

	// GetCampaign(writer http.ResponseWriter, request *http.Request)
    
        /**
         * @unauthenticated
        */
    public function getCampaign(int $id, Request $request): CampaignResource {

        $data = Campaign::query()->find($id);
        
        if(!$data) {
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "data not found"
                        ]
                        ]
                    ],400));
        };

        /**
         * A user resource.
         *
         * @status 200
         * @body CampaignResource
         */
        return new CampaignResource($data);
    }

    // UploadCampaignImage(writer http.ResponseWriter, request *http.Request)


    public function uploadCampaignImage(int $id,Request $request):JsonResponse {
        // $validator = $request->validate([
        //     'image' => 'required|image:jpeg,png,jpg,gif,svg|max:512'
        // ]);

        $curent_user = $request->get("current_user");
        if (!$curent_user){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                        ]
                        ]
                    ],400));
                }

        $data = Campaign::query()->find($id);
        if(!$data) {
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "data not found"
                        ]
                        ]
                    ],400));
        };

        $validator = Validator::make($request->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:512',
            "is_primary"=>"integer"
         ]);
         

         if ($validator->fails()) {

            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        $validator->messages()->first()
                        ]
                        ]
                    ],400));
            // return sendCustomResponse($validator->messages()->first(),  'error', 500);
         }


         $oldCampaignImages = CampaignImage::query()->where("is_primary",1)
                                ->where("campaign_id", $id)
                                ->get();
        
        //   dd($oldCampaignImages, empty($oldCampaignImages) , count($oldCampaignImages) == 0  );
         if (count($oldCampaignImages) !=0 && $request->is_primary ==1 ){
            foreach($oldCampaignImages as $old1){
                $old1->is_primary = 0;
                $old1->save();
                // dd($old1);
            }
        }


        //  dd($request);
         $uploadFolder = 'campaign_images';
         $image = $request->file('image');
        //  $image_uploaded_path = $image->store($uploadFolder, 'public');
        // or
        $name = $request->file('image')->getClientOriginalName()."_".now()->timestamp.".".$request->file('image')->getClientOriginalExtension() ;
        $name = "gori_gon" ;
        // ddd($request->file('image'));
 
         $image_uploaded_path = $image->storeAs($uploadFolder,$name , 'public');

         $uploadedImageResponse = array(
            "image_name" => basename($image_uploaded_path),
            // "image_url" => Storage::disk('public')->url($image_uploaded_path),
            "image_urls" => Storage::url($image_uploaded_path), 
            // "image_urlss" => Storage::path($image_uploaded_path) , 
            "mime" => $image->getClientMimeType()
         );

         $caIm = new CampaignImage();
         $caIm->filename = Storage::url($image_uploaded_path);
         $caIm->is_primary = $request->is_primary;
         $caIm->campaign_id = $id;

         $caIm->save();

        //  echo $uploadedImageResponse;
         return new JsonResponse(["data"=>$uploadedImageResponse]);
    }
}
