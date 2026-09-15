FROM node:22-alpine AS builder
WORKDIR /app
ENV COREPACK_ENABLE_DOWNLOAD_PROMPT=0
RUN corepack enable && corepack prepare yarn@4.17.0 --activate
COPY package.json yarn.lock .yarnrc.yml* ./
RUN yarn install --immutable
COPY . .

ARG NEXT_PUBLIC_BACKEND_URL
ENV NEXT_PUBLIC_BACKEND_URL=$NEXT_PUBLIC_BACKEND_URL
ARG GRANT_TYPE
ENV GRANT_TYPE=$GRANT_TYPE
ARG CLIENT_ID
ENV CLIENT_ID=$CLIENT_ID
ARG CLIENT_SECRET
ENV CLIENT_SECRET=$CLIENT_SECRET

RUN yarn build

FROM node:22-alpine AS runner

WORKDIR /app
ENV NODE_ENV=production
COPY --from=builder /app/public ./public
COPY --from=builder /app/.next ./.next
COPY --from=builder /app/node_modules ./node_modules
COPY --from=builder /app/package.json ./package.json

EXPOSE 3000
CMD ["./node_modules/.bin/next", "start"]
