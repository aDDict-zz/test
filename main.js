/**
* @author: robthot
**/

var c       = 0,
    snake   = {

        canvas          : {},
        direction       : "left",
        dirCache        : "left",
        speed           : 100,
        snake           : [],
        snakePos        : [],
        targetPos       : [[]],
        targetListener  : 0,
        elNumber        : 0,
        speed           : 25,

        loader: function(){
            var self = this, images = [];
            self.up = "http://dl.dropbox.com/u/4904198/snake/img/up.png";
            self.down = "http://dl.dropbox.com/u/4904198/snake/img/down.png";
            self.left = "http://dl.dropbox.com/u/4904198/snake/img/left.png";
            self.right = "http://dl.dropbox.com/u/4904198/snake/img/right.png";

            for(var i = 0; i < 4; i++){
                images[i]           = new Image();
                images[i].onload    = self.loadComplete;
            }

            images[0].src = self.up;
            images[1].src = self.down;
            images[2].src = self.left;
            images[3].src = self.right;
        },

        loadComplete: function(){
            c ++;
            if(c == 4)
                snake.initialize({
		            canvas  : [20, 15],
		            head    : [10, 2],
		            start   : [10,5]
	            });
        },

	    initialize: function(cfg){
	        var self            = this;
            self.cfg            = cfg;
            self.canvas         = {};
	        self.canvas._w      = self.cfg.canvas[0];
	        self.canvas._h      = self.cfg.canvas[1];
	        self.wrapWith       = self.cfg.head[0] + 2*self.cfg.head[1];

            document.onkeyup    = function(e){
                self.setDirection(e.keyCode);
            };

            self.terrain = document.createElement('div');
            self.terrain.setAttribute("style", ['width:',((self.canvas._w+1)*self.wrapWith),'px;height:',((self.canvas._h+1)*self.wrapWith),'px;position:absolute;left:0px;top:0px;background:#111111'].join(''));
            document.body.appendChild(self.terrain);

            self.snake.push(self.createSnakeEl());
            this.target = self.createTarget();
            self.render();
            self.timer();
	    },

	    createSnakeEl: function(){
	        var head        = this.cfg.head, elWrap, el;
            this.wrapper    = parseInt(head[0] + 2*head[1], 10);

	        if(this.elNumber == 0){
                elWrap = document.createElement('div');
                elWrap.setAttribute("id", "head");
                this.terrain.appendChild(elWrap);

                el = document.createElement('div');
                el.setAttribute("id", "head");
                elWrap.appendChild(el);
	        } else {
                elWrap = document.createElement('div');
                elWrap.setAttribute("id", ["tale",this.elNumber].join(''));
                this.terrain.appendChild(elWrap);

                el = document.createElement('div');
                el.setAttribute("id", "head");
                elWrap.appendChild(el);
	        }
	        this.cfg.wrapperStyle   = ['width:',this.wrapper,'px;height:',this.wrapper,'px;background:white;position:absolute;'].join('');
	        this.cfg.elStyle        = ['width:',head[0],'px;height:',head[0],'px;left:',head[1],'px;top:',head[1],'px;position:relative;'].join('');
	        this.cfg.taleStyle      = ['width:',head[0],'px;height:',head[0],'px;background:black;left:',head[1],'px;top:',head[1],'px;background:black;position:relative;'].join('');

            this.elNumber ++;
	        return elWrap;
	    },

	    createTarget: function(){
	        var el = document.createElement('div');
            el.setAttribute("id", "target");
            this.terrain.appendChild(el);
            this.cfg.targetStyle   = ['width:',this.wrapper,'px;height:',this.wrapper,'px;background:white;position:absolute;display:none;'].join('');
            el.setAttribute("style",this.cfg.targetStyle);
            return el;
	    },

	    setTarget: function(){
            this.targetPos[0] = [
                Math.floor(Math.random()*(this.cfg.canvas[0]+1)),
                Math.floor(Math.random()*(this.cfg.canvas[1]+1))
            ];

            this.target.style.left = this.targetPos[0][0] * (this.cfg.head[0]+(2*this.cfg.head[1]));
            this.target.style.top  = this.targetPos[0][1] * (this.cfg.head[0]+(2*this.cfg.head[1]));
            this.target.style.display = "block";
            this.targetListener = 1;
	    },

	    setDirection: function(keyCode){
            switch(keyCode){
                case 37:
                        this.direction = "left";
                break;
                case 38:
                        this.direction = "up";
                break;
                case 39:
                        this.direction = "right";
                break;
                case 40:
                        this.direction = "down";
                break;
            }
	    },

	    setPosition: function(canvasCoords){
	        return ["left:",(canvasCoords[0]*this.wrapWith),"px;top:",(canvasCoords[1]*this.wrapWith),"px;"].join('');
	    },

	    setCanvasPos: function(){
	        var self    = this,
	            thisArr = [],
	            X       = self.snakePos[0][0],
	            Y       = self.snakePos[0][1];

            switch(self.dirCache){
                case "left":
                    if(X > 0 && X <= self.canvas._w)
                        X -= 1;
                    else
                        X = self.canvas._w;
                break;
                case "right":
                    if(X >= 0 && X < self.canvas._w)
                        X += 1;
                    else
                        X = 1;
                break;
                case "down":
                    if(Y >= 0 && Y < self.canvas._h)
                        Y += 1;
                    else
                        Y = 1;
                break;
                case "up":
                    if(Y > 0 && Y <= self.canvas._h)
                        Y -= 1;
                    else
                        Y = self.canvas._h;
                break;
            }

            if(self.snakePos.length == 0)
                self.snakePos.push([X,Y]);
            else {
                self.snakePos.unshift([X,Y]);
                self.snakePos.pop();
//                self.snakePos[(self.snakePos.length - 1)] = null;
//                delete self.snakePos[(self.snakePos.length - 1)];
            }


//            thisArr = self.snakePos;
//            self.snakePos[0] = [X,Y];
//            for(var i = 0, len = (thisArr.length-1); i < len; i++){
//                self.snakePos.push(thisArr[i]);
//            }
	    },

	    render: function(){
            var self = this, wrapper, child;

            if((self.dirCache == "up" || self.dirCache == "down") && (self.direction == "left" || self.direction == "right"))
                self.dirCache = self.direction;
            else if((self.dirCache == "left" || self.dirCache == "right") && (self.direction == "up" || self.direction == "down"))
                self.dirCache = self.direction;

	        if(self.snakePos.length == 0){
	            wrapper = self.snake[0];
                child   = self.snake[0].childNodes[0];

	            self.snakePos.push([self.cfg.start[0],self.cfg.start[1]]);
	            wrapper.setAttribute("style", [self.cfg.wrapperStyle,self.setPosition([self.cfg.start[0],self.cfg.start[1]])].join(''));
	            child.setAttribute("style", [self.cfg.elStyle,"background:url(",eval("this." + this.dirCache),") no-repeat;"].join(""));
	        } else {
                self.setCanvasPos();

                for(var i = 0, len = self.snakePos.length; i < len; i++){
                    wrapper = self.snake[i];
                    child   = self.snake[i].childNodes[0];

                    wrapper.setAttribute("style", [self.cfg.wrapperStyle,self.setPosition([self.snakePos[i][0],self.snakePos[i][1]])].join(''));
	                child.setAttribute("style", [self.cfg.elStyle,"background:url(",eval("this." + this.dirCache),") no-repeat;"].join(""));
                }
	        }

            if(self.snakePos[0][0] == self.targetPos[0][0] && self.snakePos[0][1] == self.targetPos[0][1])
                self.crash();
	    },

	    crash: function(){
	        var taleEl = this.createSnakeEl();
	        taleEl.setAttribute("style", this.cfg.wrapperStyle);
	        taleEl.childNodes[0].setAttribute("style", this.cfg.taleStyle);
	        // new element
	        this.snake.push(taleEl);
	        this.snakePos.push(this.snakePos[0]);

	        this.target.style.display = "none";
            this.targetListener = 0;  console.log(this.snake, this.snakePos);
	    },

        timer: function(){
            var self    = this,
            counter = 1,
            listener = function(){
                if(counter%self.speed == 0)
                    self.render();

                if(counter%(self.speed*15) == 0)
                    if(self.targetListener == 0)
                        self.setTarget();

                if(counter == 10000)
                    counter = 1;
                else
                    counter += 1;
            },
            id = setInterval(listener, 10);

        }
};

window.onload = function(){
	snake.loader();
};

