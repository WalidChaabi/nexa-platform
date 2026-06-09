import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Docker dev uses CHOKIDAR_USEPOLLING / WATCHPACK_POLLING from compose env.
  turbopack: {},
};

export default nextConfig;
