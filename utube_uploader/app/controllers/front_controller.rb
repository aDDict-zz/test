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
      render :text => "wrong login parameters<br /><br /><a href='/'>back</a>"
    end
    # uploading video metadata
    developer_key = "AI39si5uZEI44_km0CHuO2gRt7earOQPqK-MkaXgC14yyOh3bnxxLWVdIb2yQIK_XF0h30wnyCxsa_SuoF36TL6REKdlMcc_zA"
    datas = {}
    datas["xml"] = '
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
    datas["headers"] = {
      "Host" => "gdata.youtube.com",
      "Authorization" => "GoogleLogin auth=#{@token}",
      "GData-Version" => "2",
      "X-GData-Key" => "key=#{developer_key}",
      "Content-Type" => "application/atom+xml"
    }
    resp = SendMetaData.new(datas).post()
    arr = []
    arr = resp.to_s.split("responsetoken")
    arr = arr[1].split("url")
    @responsetoken = arr[0]
    @action = arr[1]
  end

  def ready
    render :text => params.inspect  #{"action"=>"ready", "id"=>"rfHT3b1Y4Dg", "controller"=>"front", "status"=>"200"}
  end

end

class SendMetaData
  include HTTParty
  base_uri 'uploads.gdata.youtube.com'
  def initialize(datas)
    @headers = datas["headers"]
    @body = datas["xml"].strip
  end
  def post
    options = { :body => @body, :headers => @headers }
    self.class.post('/action/GetUploadToken', options)
  end
end

