FROM node:17

RUN npm install -g npm@8.5.3

WORKDIR /usr/src/app
RUN chown -R node:node /usr/src/app

USER node

ADD --chown=node:node ./app/package*.json ./

RUN npm install

CMD ["npm", "run", "watch"]
