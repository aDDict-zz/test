class FrontController < ActionController::Base

  # login form a utube authoz
  def start
  end

  # authentikacio - Google clientLogin, meta adatok feltoltese, filefeltolto form generalasa
  # require gdata
  def upload_form
    # auth
    begin
      client = GData::Client::YouTube.new
      client.clientlogin(params[:userMail], params[:password])
      client_login_handler = GData::Auth::ClientLogin.new('youtube', :account_type => 'HOSTED_OR_GOOGLE')
      @token = client_login_handler.get_token(params[:userMail], params[:password], 'google-RailsArticleSample-v1')
      client = GData::Client::Base.new(:auth_handler => client_login_handler)
    rescue GData::Client::AuthorizationError
      render :text => "wrong login parameters, use your youtube account<br /><br /><a href='/'>back</a>"
    end
    # uploading video metadata
    @developer_key = "AI39si5uZEI44_km0CHuO2gRt7earOQPqK-MkaXgC14yyOh3bnxxLWVdIb2yQIK_XF0h30wnyCxsa_SuoF36TL6REKdlMcc_zA"
    datas = self.getParamsForRequest
    resp = SendMetaData.new(datas).post()
    # valtozok beallitasa az upload formhoz
    arr = []
    arr = resp.to_s.split("responsetoken")
    arr = arr[1].split("url")
    @responsetoken = arr[0]
    @action = arr[1]
    # session valtozok initializalasa
    session[:userMail] = params[:userMail]
    session[:password] = params[:password]
    session[:developer_key] = @developer_key
  end

  # ide kuld vissza a youtube a video upload utan
  def ready
    if !params[:status] || params[:status] != "200"
      render :text => "Upload failed, something went wrong<br /><br /><a href='/'>back</a>"
    else
      @video_id = params[:id]
      @user_mail = session[:userMail]
      @password = session[:password]
      @developer_key = session[:developer_key]
    end
  end

  # odaadja a videohoz tartozo adatokat, a datumokat onkenyesen valasztottam
  def datas
    arr = []
    arr[0] = {
      "text" => "Lengyel jogászok Budapesten - A küldöttség tagjai megtekintették az országház épületét.",
      "start" => "Jan 02 1936 07:00:00 GMT",
      "end" => "Jan 02 1936 09:00:00 GMT",
      "seek_from" => 36,
      "location" => ["Országház", [47.507051, 19.046671, 13]]
    }
    arr[1] = {
      "text" => "Jégkirálynő - Charlotte német korcsolyabajnoknő bemutatója a budapesti műjégpályán.",
      "start" => "Jan 04 1936 07:00:00 GMT",
      "end" => "Jan 04 1936 09:00:00 GMT",
      "seek_from" => 62,
      "location" => ["Városligeti műjégpálya", [47.52, 19.08, 13]]
    }
    arr[2] = {
      "text" => "Gyógyító méreg - Az igen elterjedt és kínzó reuma ellen a gyógyszervegyészet modern módon alkalmaz régi módszereket.",
      "start" => "Jan 06 1936 07:00:00 GMT",
      "end" => "Jan 06 1936 09:00:00 GMT",
      "seek_from" => 114,
      "location" => ""
    }
    arr[3] = {
      "text" => "Anglia-Magyarország 6:2 (3:1) - A magyar futballválogatott elismerten szép küzdelmet vívott az angol tizeneggyel.",
      "start" => "Jan 08 1936 07:00:00 GMT",
      "end" => "Jan 08 1936 09:00:00 GMT",
      "seek_from" => 186,
      "location" => ["Arsenal-pálya", [51.55, -0.10, 9]]
    }
    arr[4] = {
      "text" => "A leghosszabb híd - 77 millió dollár költséggel építették meg a San Franciscoi öböl partjait összekötő 13,6 km hosszú hidat.",
      "start" => "Jan 09 1936 09:00:00 GMT",
      "end" => "Jan 09 1936 15:00:00 GMT",
      "seek_from" => 226,
      "location" => ["Golden Gate-híd", [37.82, -122.48, 10]]
    }
    arr[5] = {
      "text" => "A Kristálypalota égése - London 80 esztendős világhírű üvegpalotája a tűz martalékává lett.",
      "start" => "Jan 09 1936 19:00:00 GMT",
      "end" => "Jan 09 1936 23:00:00 GMT",
      "seek_from" => 269,
      "location" => ["Kristálypalota", [51.41, -0.07, 9]]
    }
    arr[6] = {
      "text" => "Madrid ostroma - A nemzeti csapatok Varela tábornok vezetése alatt heves küzdelmet vívnak a spanyol fővárosért.",
      "start" => "Jan 10 1936 09:00:00 GMT",
      "end" => "Jan 10 1936 15:00:00 GMT",
      "seek_from" => 294,
      "location" => ["Casa de Campo", [40.42, -3.74, 9]]
    }
    arr[7] = {
      "text" => "Japán-német egyezmény - Von Ribbentrop német és Mushakoji japán nagykövetek írták alá az antibolsevista egyezményt.",
      "start" => "Jan 11 1936 09:00:00 GMT",
      "end" => "Jan 11 1936 17:00:00 GMT",
      "seek_from" => 343,
      "location" => ["Berlin", [52.51, 13.37, 3]]
    }
    arr[8] = {
      "text" => "Stromboli - Az állandóan működő olasz tűzhányó, a Stromboli évenként többször félemlíti meg a kis sziget halászait.",
      "start" => "Jan 11 1936 18:00:00 GMT",
      "end" => "Jan 11 1936 24:00:00 GMT",
      "seek_from" => 389,
      "location" => ["Stromboli", [38.80, 15.24, 3]]
    }
    arr[9] = {
      "text" => "Lengyel szabadságünnep - Lengyelország függetlenségének 18-ik évfordulóját nagy katonai parádéval ünnepelte meg.",
      "start" => "Jan 20 1936 10:00:00 GMT",
      "end" => "Jan 20 1936 19:00:00 GMT",
      "seek_from" => 441,
      "location" => ["Lengyelország", [51.91, 19.13, 3]]
    }
    render :text => arr.to_json
  end

  # video meta adatok
  def getParamsForRequest
    datas = {}
    datas["headers"] = {
      "Host" => "gdata.youtube.com",
      "Authorization" => "GoogleLogin auth=#{@token}",
      "GData-Version" => "2",
      "X-GData-Key" => "key=#{@developer_key}",
      "Content-Type" => "application/atom+xml"
    }
    datas["body"] = '
      <?xml version="1.0"  encoding="UTF-8"?>
      <entry xmlns="http://www.w3.org/2005/Atom"
        xmlns:media="http://search.yahoo.com/mrss/"
        xmlns:yt="http://gdata.youtube.com/schemas/2007">
        <media:group>
          <media:title type="plain">title</media:title>
          <media:description type="plain">
            description
          </media:description>
          <media:category
            scheme="http://gdata.youtube.com/schemas/2007/categories.cat">People
          </media:category>
          <media:keywords>test</media:keywords>
        </media:group>
      </entry>
    '
    return datas
  end

end

# a request felepitese
# require HTTParty
class SendMetaData
  include HTTParty
  base_uri 'uploads.gdata.youtube.com'
  def initialize datas
    @headers = datas["headers"]
    @body = datas["body"].strip
  end
  def post
    options = {:body => @body, :headers => @headers}
    self.class.post('/action/GetUploadToken', options)
  end
end

